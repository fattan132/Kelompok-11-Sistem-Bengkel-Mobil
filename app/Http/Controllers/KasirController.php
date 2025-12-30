<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\ServiceBookingItem;
use App\Models\ServiceFeeTemplate;
use App\Models\User;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function dashboard()
    {
        $todayBookings = ServiceBooking::whereDate('created_at', today())->count();
        $pendingBookings = ServiceBooking::where('status', 'pending')->count();
        $ongoingBookings = ServiceBooking::where('status', 'ongoing')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $recentBookings = ServiceBooking::with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('kasir.dashboard', compact(
            'todayBookings',
            'pendingBookings',
            'ongoingBookings',
            'totalCustomers',
            'recentBookings'
        ));
    }

    // Services Management
    public function listServices()
    {
        $services = Service::orderBy('name')->paginate(10);
        return view('kasir.services.list', compact('services'));
    }

    public function createServiceForm()
    {
        return view('kasir.services.create');
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'points_earned' => 'required|numeric|min:0',
            'difficulty_level' => 'required|in:easy,hard,custom',
            'has_custom_fee' => 'nullable|boolean',
        ]);

        $validated['has_custom_fee'] = isset($validated['has_custom_fee']) ? true : false;

        Service::create($validated);

        return redirect()->route('kasir.services')->with('success', 'Layanan berhasil ditambahkan');
    }

    public function editServiceForm(Service $service)
    {
        return view('kasir.services.edit', compact('service'));
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'points_earned' => 'required|numeric|min:0',
            'difficulty_level' => 'required|in:easy,hard,custom',
            'has_custom_fee' => 'nullable|boolean',
        ]);

        $validated['has_custom_fee'] = isset($validated['has_custom_fee']) ? true : false;

        $service->update($validated);

        return redirect()->route('kasir.services')->with('success', 'Layanan berhasil diperbarui');
    }

    public function deleteService(Service $service)
    {
        $service->delete();
        return redirect()->route('kasir.services')->with('success', 'Layanan berhasil dihapus');
    }

    // Bookings Management
    public function listBookings()
    {
        $query = ServiceBooking::with('user', 'service');

        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter by payment status
        if (request('payment_status')) {
            $query->where('payment_status', request('payment_status'));
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('kasir.bookings.list', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, ServiceBooking $booking)
    {
        \Log::info('updateBookingStatus called', [
            'booking_id' => $booking->id,
            'request_data' => $request->all()
        ]);
        
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,confirmed,ongoing,completed,cancelled',
                'mechanic_notes' => 'nullable|string',
                'services' => 'nullable|array',
                'services.*' => 'exists:services,id',
            ]);

            \Log::info('Validation passed', ['validated' => $validated]);

            // Update status dan mechanic notes
            $booking->status = $validated['status'];
            $booking->mechanic_notes = $validated['mechanic_notes'] ?? $booking->mechanic_notes;

            // Tambahkan layanan baru (tidak menghapus yang lama)
            if (isset($validated['services']) && !empty($validated['services'])) {
                $selectedServiceIds = $validated['services'];
                
                // Ambil service yang sudah ada di booking
                $existingServiceIds = $booking->items()->pluck('service_id')->toArray();
                
                // Cari service baru yang belum ada
                $newServiceIds = array_diff($selectedServiceIds, $existingServiceIds);
                
                if (!empty($newServiceIds)) {
                    $newServices = Service::whereIn('id', $newServiceIds)->get();
                    
                    // Tambah items baru
                    $itemsPayload = $newServices->map(function ($srv) use ($booking) {
                        return [
                            'service_booking_id' => $booking->id,
                            'service_id' => $srv->id,
                            'price' => $srv->price,
                            'points_earned' => $srv->points_earned,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    })->all();
                    
                    if (!empty($itemsPayload)) {
                        ServiceBookingItem::insert($itemsPayload);
                    }
                }
                
                // Hitung ulang total dari SEMUA items di booking
                // PENTING: Load items tanpa refresh booking untuk tidak overwrite status
                $allItems = ServiceBookingItem::where('service_booking_id', $booking->id)
                    ->with('service')
                    ->get();
                
                // Hitung subtotal dari semua layanan
                $serviceTotal = $allItems->sum('price');
                
                // Hitung service fee berdasarkan difficulty level dari setiap service
                $serviceFee = 0;
                foreach ($allItems as $item) {
                    $serviceFee += $item->service->getAutoFee();
                }
                
                // Hitung dengan PPN 11%
                $subtotal = $serviceTotal + $serviceFee;
                $taxAmount = $subtotal * 0.11;
                $totalPrice = $subtotal + $taxAmount;
                
                $booking->total_price = $totalPrice;
                $booking->service_fee = $serviceFee;
                $booking->subtotal = $subtotal;
                $booking->tax_amount = $taxAmount;
                
                // Update service_id utama (service pertama dari items)
                if ($allItems->count() > 0) {
                    $booking->service_id = $allItems->first()->service_id;
                }
            }

            // Jika status completed, berikan poin ke user
            if ($validated['status'] == 'completed' && $booking->points_given == 0 && $booking->payment_status == 'paid') {
                $totalPoints = 0;
                // Hitung poin dari items tanpa refresh
                $items = ServiceBookingItem::where('service_booking_id', $booking->id)->get();
                if ($items && $items->count() > 0) {
                    $totalPoints = $items->sum('points_earned');
                }
                $booking->user->update(['points' => $booking->user->points + $totalPoints]);
                $booking->points_given = $totalPoints;
                $booking->completed_at = now();
            }

            // PENTING: Simpan booking apakah ada perubahan layanan atau tidak
            $booking->save();

            \Log::info('Booking saved successfully', [
                'booking_id' => $booking->id,
                'new_status' => $booking->status
            ]);

            return redirect()->route('kasir.bookings.detail', $booking->id)->with('success', 'Status pemesanan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('kasir.bookings.detail', $booking->id)
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function createBookingForm()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        
        // Generate time slots
        $timeSlots = $this->getAvailableTimeSlots();
        
        return view('kasir.bookings.create', compact('customers', 'services', 'timeSlots'));
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

    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'vehicle_model' => 'required|string|max:100',
            'vehicle_number' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cash,bank_transfer,e_wallet',
            'payment_status' => 'required|in:unpaid,paid',
            'status' => 'required|in:pending,confirmed,ongoing,completed,cancelled',
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
        $totalPoints = $selectedServices->sum('points_earned');

        // Generate unique receipt number
        $receipt_number = 'INV-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        $bookingData = [
            'user_id' => $validated['user_id'],
            'service_id' => $primaryServiceId,
            'vehicle_model' => $validated['vehicle_model'],
            'vehicle_number' => $validated['vehicle_number'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'notes' => $validated['notes'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => $validated['payment_status'],
            'status' => $validated['status'],
            'total_price' => $totalPrice,
            'points_given' => 0,
            'receipt_number' => $receipt_number,
        ];

        // Jika langsung completed dan paid, berikan poin
        if ($validated['status'] == 'completed' && $validated['payment_status'] == 'paid') {
            $user = User::findOrFail($validated['user_id']);
            $user->update(['points' => $user->points + $totalPoints]);
            $bookingData['points_given'] = $totalPoints;
            $bookingData['completed_at'] = now();
        }

        $booking = ServiceBooking::create($bookingData);

        // Buat items untuk setiap layanan
        $itemsPayload = $selectedServices->map(function ($srv) {
            return [
                'service_id' => $srv->id,
                'price' => $srv->price,
                'points_earned' => $srv->points_earned,
            ];
        })->all();

        $booking->items()->createMany($itemsPayload);

        return redirect()->route('kasir.bookings.print', $booking->id)->with('success', 'Pemesanan berhasil dibuat');
    }

    public function bookingDetail(ServiceBooking $booking)
    {
        $booking->load(['user', 'service', 'items.service']);
        $services = Service::orderBy('name')->get();
        $feeTemplates = ServiceFeeTemplate::where('is_active', true)->orderBy('fee')->get();
        return view('kasir.bookings.detail', compact('booking', 'services', 'feeTemplates'));
    }

    public function deleteBooking(ServiceBooking $booking)
    {
        $booking->delete();
        return redirect()->route('kasir.bookings')->with('success', 'Booking berhasil dihapus');
    }

    public function printReceipt(ServiceBooking $booking)
    {
        return view('kasir.bookings.print', compact('booking'));
    }

    // Users/Customers Management
    public function listUsers(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('serviceBookings');

        // Search by ID
        if ($request->filled('search_id')) {
            $searchId = $request->search_id;
            $query->where('id', $searchId);
        }

        $users = $query->paginate(10);
        return view('kasir.users.list', compact('users'));
    }

    public function createUserForm()
    {
        return view('kasir.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'customer';
        $validated['points'] = 0;

        User::create($validated);

        return redirect()->route('kasir.users')->with('success', 'Customer berhasil ditambahkan');
    }

    public function userDetails(User $user)
    {
        if ($user->role !== 'customer') {
            abort(403);
        }
        $bookings = $user->serviceBookings()->with('service')->paginate(5);
        return view('kasir.users.detail', compact('user', 'bookings'));
    }

    public function updateUser(Request $request, User $user)
    {
        if ($user->role !== 'customer') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $user->update($validated);

        return redirect()->route('kasir.users.detail', $user->id)->with('success', 'Customer berhasil diupdate');
    }

    public function deleteUser(User $user)
    {
        if ($user->role !== 'customer') {
            abort(403);
        }
        $user->delete();
        return redirect()->route('kasir.users')->with('success', 'Customer berhasil dihapus');
    }

    // Backward compatibility methods
    public function listCustomers()
    {
        return $this->listUsers();
    }

    public function createCustomerForm()
    {
        return $this->createUserForm();
    }

    public function storeCustomer(Request $request)
    {
        return $this->storeUser($request);
    }

    public function customerDetails(User $customer)
    {
        return $this->userDetails($customer);
    }
}

