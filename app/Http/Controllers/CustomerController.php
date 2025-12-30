<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\Voucher;
use App\Models\UserVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $totalBookings = $user->serviceBookings()->count();

        $activeBookings = $user->serviceBookings()
            ->whereIn('status', ['pending', 'confirmed', 'ongoing'])
            ->count();

        $recentBookings = $user->serviceBookings()
            ->with('service')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'totalBookings',
            'activeBookings',
            'recentBookings'
        ));
    }

    public function services()
    {
        $services = Service::all();
        return view('customer.services', compact('services'));
    }

    public function bookingForm(Service $service)
    {
        $services = Service::orderBy('name')->get();
        
        // Get user's available vouchers
        $myVouchers = auth()->user()->userVouchers()
            ->with('voucher.freeService')
            ->where('is_used', false)
            ->get()
            ->filter(function ($userVoucher) {
                return $userVoucher->isValid();
            });

        // Generate time slots with availability
        $timeSlots = $this->getAvailableTimeSlots();

        return view('customer.booking', [
            'service' => $service,
            'services' => $services,
            'myVouchers' => $myVouchers,
            'timeSlots' => $timeSlots,
        ]);
    }

    // Helper method untuk get available time slots
    private function getAvailableTimeSlots($date = null)
    {
        $timeSlots = [];
        $date = $date ?? now()->format('Y-m-d');
        
        for ($hour = 8; $hour <= 17; $hour++) {
            $time = sprintf('%02d:00', $hour);
            $bookingCount = ServiceBooking::where('booking_date', $date)
                ->where('booking_time', $time)
                ->whereNotIn('status', ['cancelled'])
                ->count();
            
            $timeSlots[] = [
                'time' => $time,
                'available' => 4 - $bookingCount,
                'total' => 4,
                'is_full' => $bookingCount >= 4
            ];
        }
        
        return $timeSlots;
    }

    public function storeBooking(Request $request, Service $service)
    {
        $validated = $request->validate([
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'vehicle_model' => 'required|string|max:100',
            'vehicle_number' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'voucher_code' => 'nullable|string',
        ]);

        // Cek slot availability - maksimal 4 mobil per jam
        $bookingCount = ServiceBooking::where('booking_date', $validated['booking_date'])
            ->where('booking_time', $validated['booking_time'])
            ->whereNotIn('status', ['cancelled'])
            ->count();

        if ($bookingCount >= 4) {
            return back()->withErrors(['booking_time' => 'Slot waktu ini sudah penuh. Maksimal 4 mobil per jam. Silakan pilih waktu lain.'])
                ->withInput();
        }

        $selectedServices = Service::whereIn('id', $validated['services'])
            ->orderBy('name')
            ->get();

        if ($selectedServices->isEmpty()) {
            return back()->withErrors(['services' => 'Pilih minimal satu layanan.']);
        }

        $primaryServiceId = $selectedServices->first()->id;
        $totalPrice = $selectedServices->sum('price');
        $discount = 0;
        $userVoucher = null;

        // Process voucher if provided
        if (!empty($validated['voucher_code'])) {
            $userVoucher = UserVoucher::where('voucher_code', $validated['voucher_code'])
                ->where('user_id', auth()->id())
                ->where('is_used', false)
                ->with('voucher.freeService')
                ->first();

            if (!$userVoucher) {
                return back()->withErrors(['voucher_code' => 'Kode voucher tidak valid atau sudah digunakan.']);
            }

            if (!$userVoucher->isValid()) {
                return back()->withErrors(['voucher_code' => 'Voucher sudah kadaluarsa atau tidak valid.']);
            }

            $voucher = $userVoucher->voucher;

            // Calculate discount based on voucher type
            if ($voucher->type === 'discount_percentage') {
                $discount = ($totalPrice * $voucher->value) / 100;
            } elseif ($voucher->type === 'discount_fixed') {
                $discount = min($voucher->value, $totalPrice); // Don't exceed total price
            } elseif ($voucher->type === 'free_service' && $voucher->freeService) {
                // Check if free service is in selected services
                $freeServiceInCart = $selectedServices->where('id', $voucher->free_service_id)->first();
                if ($freeServiceInCart) {
                    $discount = $freeServiceInCart->price;
                } else {
                    return back()->withErrors(['voucher_code' => 'Voucher ini hanya untuk layanan ' . $voucher->freeService->name . '. Silakan tambahkan layanan tersebut.']);
                }
            }
        }

        $finalPrice = max(0, $totalPrice - $discount);

        DB::beginTransaction();
        try {
            $booking = ServiceBooking::create([
                'user_id' => auth()->id(),
                'service_id' => $primaryServiceId,
                'vehicle_model' => $validated['vehicle_model'],
                'vehicle_number' => $validated['vehicle_number'],
                'booking_date' => $validated['booking_date'],
                'booking_time' => $validated['booking_time'],
                'notes' => $validated['notes'],
                'total_price' => $finalPrice,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            $itemsPayload = $selectedServices->map(function ($srv) {
                return [
                    'service_id' => $srv->id,
                    'price' => $srv->price,
                    'points_earned' => $srv->points_earned,
                ];
            })->all();

            $booking->items()->createMany($itemsPayload);

            // Mark voucher as used if applied
            if ($userVoucher) {
                $userVoucher->update([
                    'is_used' => true,
                    'used_at' => now(),
                    'booking_id' => $booking->id,
                ]);
            }

            DB::commit();

            // ⚠️ route model binding AMAN
            return redirect()->route('customer.payment', $booking);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membuat booking.');
        }
    }

    public function payment(ServiceBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Cek apakah status sudah completed
        if ($booking->status !== 'completed') {
            return redirect()->route('customer.bookings')
                ->with('error', 'Pembayaran hanya bisa dilakukan setelah servis selesai dikerjakan.');
        }

        // Cek apakah sudah dibayar
        if ($booking->payment_status === 'paid') {
            return redirect()->route('customer.receipt', $booking)
                ->with('info', 'Booking ini sudah dibayar.');
        }

        $booking->loadMissing(['service', 'items.service']);

        return view('customer.payment', compact('booking'));
    }

    public function confirmPayment(Request $request, ServiceBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Cek apakah status sudah completed
        if ($booking->status !== 'completed') {
            return redirect()->route('customer.bookings')
                ->with('error', 'Pembayaran hanya bisa dilakukan setelah servis selesai dikerjakan.');
        }

        // Cek apakah sudah dibayar
        if ($booking->payment_status === 'paid') {
            return redirect()->route('customer.receipt', $booking)
                ->with('info', 'Booking ini sudah dibayar.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,e_wallet',
        ]);

        $booking->loadMissing(['service', 'items.service']);

        // Generate receipt number
        $receipt_number = 'INV-' . now()->format('Ymd') . '-' . str_pad(
            ServiceBooking::whereDate('created_at', today())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        $totalPoints = $booking->items->sum('points_earned');
        if ($totalPoints === 0) {
            $totalPoints = $booking->service?->points_earned ?? 0;
        }

        // Update booking
        $booking->update([
            'payment_status' => 'paid',
            'payment_method' => $validated['payment_method'],
            'receipt_number' => $receipt_number,
            'points_given' => $totalPoints,
        ]);

        // ✅ ADD POINTS (AMAN - TIDAK ERROR)
        $user = auth()->user();

        $user->increment('points', $totalPoints);

        return redirect()->route('customer.receipt', $booking);
    }

    public function receipt(ServiceBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->loadMissing(['service', 'items.service']);

        return view('customer.receipt', compact('booking'));
    }

    public function myBookings()
    {
        $bookings = auth()->user()
            ->serviceBookings()
            ->with(['service', 'items.service'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.bookings', compact('bookings'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Check current password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password lama tidak sesuai'
                ]);
            }

            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        unset($validated['current_password']);

        $user->update($validated);

        return redirect()
            ->route('customer.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    public function rewards()
    {
        $user = auth()->user();
        $currentPoints = $user->points;

        // Get available vouchers
        $availableVouchers = Voucher::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->orderBy('points_required', 'asc')
            ->get();

        // Get user's vouchers
        $myVouchers = $user->userVouchers()
            ->with('voucher.freeService')
            ->where('is_used', false)
            ->latest()
            ->get();

        // Define reward tiers
        $tiers = [
            [
                'name' => 'Bronze',
                'min_points' => 0,
                'max_points' => 99,
                'icon' => 'fas fa-medal',
                'color' => '#CD7F32',
                'benefits' => [
                    'Diskon 5% untuk servis berikutnya',
                    'Prioritas notifikasi promo',
                    'Akumulasi poin dari setiap transaksi'
                ]
            ],
            [
                'name' => 'Silver',
                'min_points' => 100,
                'max_points' => 499,
                'icon' => 'fas fa-medal',
                'color' => '#C0C0C0',
                'benefits' => [
                    'Diskon 10% untuk servis berikutnya',
                    'Prioritas booking di jam sibuk',
                    'Gratis oli filter setiap 3x servis',
                    'Bonus poin 2x di hari ulang tahun'
                ]
            ],
            [
                'name' => 'Gold',
                'min_points' => 500,
                'max_points' => 999,
                'icon' => 'fas fa-crown',
                'color' => '#FFD700',
                'benefits' => [
                    'Diskon 15% untuk servis berikutnya',
                    'Antrean prioritas tinggi',
                    'Gratis cuci mobil premium setiap servis',
                    'Gratis oli mesin setiap 5x servis',
                    'Bonus poin 3x di hari ulang tahun'
                ]
            ],
            [
                'name' => 'Platinum',
                'min_points' => 1000,
                'max_points' => PHP_INT_MAX,
                'icon' => 'fas fa-gem',
                'color' => '#E5E4E2',
                'benefits' => [
                    'Diskon 20% untuk servis berikutnya',
                    'Akses VIP tanpa antrean',
                    'Gratis cuci mobil premium + detailing',
                    'Gratis oli mesin setiap 3x servis',
                    'Gratis pengecekan menyeluruh',
                    'Bonus poin 5x di hari ulang tahun',
                    'Voucher servis gratis setiap 1000 poin'
                ]
            ]
        ];

        // Determine current tier
        $currentTier = null;
        $nextTier = null;
        
        foreach ($tiers as $index => $tier) {
            if ($currentPoints >= $tier['min_points'] && $currentPoints <= $tier['max_points']) {
                $currentTier = $tier;
                if (isset($tiers[$index + 1])) {
                    $nextTier = $tiers[$index + 1];
                }
                break;
            }
        }

        // Calculate progress to next tier
        $progressPercentage = 0;
        $pointsToNextTier = 0;
        
        if ($nextTier) {
            $pointsToNextTier = $nextTier['min_points'] - $currentPoints;
            $tierRange = $nextTier['min_points'] - $currentTier['min_points'];
            $progressPercentage = (($currentPoints - $currentTier['min_points']) / $tierRange) * 100;
        } else {
            $progressPercentage = 100;
        }

        return view('customer.rewards', compact(
            'currentPoints',
            'tiers',
            'currentTier',
            'nextTier',
            'progressPercentage',
            'pointsToNextTier',
            'availableVouchers',
            'myVouchers'
        ));
    }

    public function redeemVoucher(Request $request)
    {
        $validated = $request->validate([
            'voucher_id' => 'required|exists:vouchers,id'
        ]);

        $voucher = Voucher::findOrFail($validated['voucher_id']);
        $user = auth()->user();

        DB::beginTransaction();
        try {
            // Check if voucher can be redeemed
            if (!$voucher->canBeRedeemedBy($user)) {
                return back()->with('error', 'Voucher tidak dapat ditukar. Periksa poin Anda atau ketersediaan voucher.');
            }

            // Deduct points
            $user->points -= $voucher->points_required;
            $user->save();

            // Generate unique voucher code
            $voucherCode = strtoupper($voucher->code . '-' . uniqid());

            // Create user voucher
            UserVoucher::create([
                'user_id' => $user->id,
                'voucher_id' => $voucher->id,
                'voucher_code' => $voucherCode,
                'is_used' => false,
            ]);

            // Increment redemption count
            $voucher->increment('times_redeemed');

            DB::commit();

            return back()->with('success', 'Voucher berhasil ditukar! Gunakan kode: ' . $voucherCode);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menukar voucher.');
        }
    }
}
