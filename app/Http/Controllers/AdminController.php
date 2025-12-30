<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        $total_services = Service::count();
        $total_users = User::where('role', 'user')->count();
        $total_bookings = ServiceBooking::count();
        $pending_bookings = ServiceBooking::where('status', 'pending')->count();
        $completed_bookings = ServiceBooking::where('status', 'completed')->count();
        $total_revenue = ServiceBooking::where('status', 'completed')->sum('total_price');

        return view('admin.dashboard', compact(
            'total_services',
            'total_users',
            'total_bookings',
            'pending_bookings',
            'completed_bookings',
            'total_revenue'
        ));
    }

    // List Services
    public function listServices()
    {
        $services = Service::paginate(10);
        return view('admin.services.list', compact('services'));
    }

    // Create Service Form
    public function createServiceForm()
    {
        return view('admin.services.create');
    }

    // Store Service
    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'points_earned' => 'required|numeric|min:0',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services')->with('success', 'Layanan berhasil ditambahkan');
    }

    // Edit Service Form
    public function editServiceForm(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    // Update Service
    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'points_earned' => 'required|numeric|min:0',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services')->with('success', 'Layanan berhasil diperbarui');
    }

    // Delete Service
    public function deleteService(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services')->with('success', 'Layanan berhasil dihapus');
    }

    // List Bookings
    public function listBookings()
    {
        $bookings = ServiceBooking::with('user', 'service')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.bookings.list', compact('bookings'));
    }

    // Update Booking Status
    public function updateBookingStatus(Request $request, ServiceBooking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $booking->status = $validated['status'];

        // Jika status completed, berikan poin ke user
        if ($validated['status'] == 'completed' && $booking->points_given == 0) {
            $points = $booking->service->points_earned;
            $booking->user->update(['points' => $booking->user->points + $points]);
            $booking->points_given = $points;
            $booking->completed_at = now();
        }

        $booking->save();

        return redirect()->route('admin.bookings')->with('success', 'Status pemesanan berhasil diperbarui');
    }

    // List Users
    public function listUsers()
    {
        $users = User::where('role', 'user')->paginate(10);
        return view('admin.users.list', compact('users'));
    }

    // View User Details
    public function userDetails(User $user)
    {
        if ($user->role !== 'user') {
            abort(403);
        }
        $bookings = $user->serviceBookings()->paginate(5);
        return view('admin.users.detail', compact('user', 'bookings'));
    }

    // Create User Form
    public function createUserForm()
    {
        return view('admin.users.create');
    }

    // Store User
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
        $validated['role'] = 'user';
        $validated['points'] = 0;

        User::create($validated);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan');
    }

    // Create Booking For User Form
    public function createBookingForm()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        return view('admin.bookings.create', compact('users', 'services'));
    }

    // Store Booking For User
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_plate' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $validated['total_price'] = $service->price;
        $validated['points_given'] = 0;

        // Jika langsung completed, berikan poin
        if ($validated['status'] == 'completed') {
            $user = User::findOrFail($validated['user_id']);
            $user->update(['points' => $user->points + $service->points_earned]);
            $validated['points_given'] = $service->points_earned;
            $validated['completed_at'] = now();
        }

        ServiceBooking::create($validated);

        return redirect()->route('admin.bookings')->with('success', 'Pemesanan berhasil dibuat');
    }
}
