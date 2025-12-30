<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceBooking;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Dashboard User
    public function dashboard()
    {
        $user = auth()->user();
        $total_points = $user->points;
        $total_bookings = $user->serviceBookings()->count();
        $completed_bookings = $user->serviceBookings()->where('status', 'completed')->count();
        $pending_bookings = $user->serviceBookings()->where('status', 'pending')->count();

        return view('user.dashboard', compact(
            'total_points',
            'total_bookings',
            'completed_bookings',
            'pending_bookings'
        ));
    }

    // List Services untuk Booking
    public function services()
    {
        $services = Service::paginate(10);
        return view('user.services', compact('services'));
    }

    // Booking Form
    public function bookingForm(Service $service)
    {
        return view('user.booking', compact('service'));
    }

    // Store Booking
    public function storeBooking(Request $request, Service $service)
    {
        $validated = $request->validate([
            'vehicle_model' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:50',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['service_id'] = $service->id;
        $validated['total_price'] = $service->price;
        $validated['status'] = 'pending';

        ServiceBooking::create($validated);

        return redirect()->route('user.bookings')->with('success', 'Pemesanan berhasil dibuat');
    }

    // List My Bookings
    public function myBookings()
    {
        $bookings = auth()->user()->serviceBookings()
            ->with('service')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('user.bookings', compact('bookings'));
    }

    // Cancel Booking
    public function cancelBooking(ServiceBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'pending') {
            $booking->update(['status' => 'cancelled']);
            return redirect()->route('user.bookings')->with('success', 'Pemesanan berhasil dibatalkan');
        }

        return redirect()->route('user.bookings')->with('error', 'Tidak bisa membatalkan pemesanan yang sudah diproses');
    }

    // View Profile
    public function profile()
    {
        $user = auth()->user();
        return view('user.profile', compact('user'));
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui');
    }
}
