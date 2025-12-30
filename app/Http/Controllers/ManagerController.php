<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $todayRevenue = ServiceBooking::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_price');
        
        $monthRevenue = ServiceBooking::where('payment_status', 'paid')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total_price');
        
        $totalTransactions = ServiceBooking::where('payment_status', 'paid')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        
        // Top 5 layanan terpopuler
        $topServices = ServiceBooking::select('service_id', DB::raw('count(*) as total'))
            ->where('payment_status', 'paid')
            ->groupBy('service_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->with('service')
            ->get();
        
        // Transaksi terbaru
        $recentTransactions = ServiceBooking::with(['user', 'service'])
            ->where('payment_status', 'paid')
            ->whereNotNull('receipt_number')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Data chart 7 hari terakhir
        $chartData = [
            'labels' => [],
            'data' => []
        ];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData['labels'][] = $date->format('d M');
            $chartData['data'][] = ServiceBooking::where('payment_status', 'paid')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->sum('total_price');
        }

        return view('manager.dashboard', compact(
            'todayRevenue',
            'monthRevenue',
            'totalTransactions',
            'totalCustomers',
            'topServices',
            'recentTransactions',
            'chartData'
        ));
    }

    // Sales Report
    public function salesReport(Request $request)
    {
        $period = $request->get('period', 'daily');
        $start_date = $request->get('start_date', date('Y-m-01'));
        $end_date = $request->get('end_date', date('Y-m-d'));

        // Gunakan transaksi yang sudah dibayar; fallback ke created_at jika completed_at null
        $baseQuery = ServiceBooking::with('user', 'service')
            ->where('payment_status', 'paid')
            ->whereBetween(DB::raw('COALESCE(completed_at, created_at)'), [$start_date, $end_date]);

        // Data lengkap untuk agregasi
        $bookingsAll = $baseQuery->get();

        // Paginate untuk tabel
        $bookings = (clone $baseQuery)
            ->orderBy(DB::raw('COALESCE(completed_at, created_at)'), 'desc')
            ->paginate(15)
            ->withQueryString();

        $total_revenue = $bookingsAll->sum('total_price');
        $total_transactions = $bookingsAll->count();
        $avg_transaction = $total_transactions > 0 ? $total_revenue / $total_transactions : 0;

        // Group by service
        $by_service = $bookingsAll->groupBy('service_id')->map(function ($items) {
            $service = $items->first()->service;
            return [
                'name' => $service?->name ?? 'Layanan',
                'count' => $items->count(),
                'revenue' => $items->sum('total_price'),
            ];
        });

        // Group by payment method
        $by_payment = $bookingsAll->groupBy('payment_method')->map(function ($items, $key) {
            return [
                'method' => $key ?? '-',
                'count' => $items->count(),
                'revenue' => $items->sum('total_price'),
            ];
        });

        return view('manager.reports.sales', compact(
            'bookings',
            'total_revenue',
            'total_transactions',
            'avg_transaction',
            'by_service',
            'by_payment',
            'period',
            'start_date',
            'end_date'
        ));
    }

    // Customer Report
    public function customerReport()
    {
        $customers = User::where('role', 'customer')
            ->withCount('serviceBookings')
            ->withSum(['serviceBookings as total_spent' => function ($query) {
                $query->where('status', 'completed')->where('payment_status', 'paid');
            }], 'total_price')
            ->orderBy('service_bookings_count', 'desc')
            ->paginate(20);

        return view('manager.reports.customers', compact('customers'));
    }

    // Kasir Management
    public function listKasir()
    {
        $kasirs = User::where('role', 'kasir')->paginate(10);
        return view('manager.kasir.list', compact('kasirs'));
    }

    public function createKasirForm()
    {
        return view('manager.kasir.create');
    }

    public function storeKasir(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'kasir';
        $validated['points'] = 0;

        User::create($validated);

        return redirect()->route('manager.kasir')->with('success', 'Kasir berhasil ditambahkan');
    }

    public function deleteKasir(User $kasir)
    {
        if ($kasir->role !== 'kasir') {
            abort(403);
        }

        $kasir->delete();
        return redirect()->route('manager.kasir')->with('success', 'Kasir berhasil dihapus');
    }

    // Admin Management (backward compatibility)
    public function listAdmins()
    {
        return $this->listKasir();
    }

    public function createAdminForm()
    {
        return $this->createKasirForm();
    }

    public function storeAdmin(Request $request)
    {
        return $this->storeKasir($request);
    }

    public function deleteAdmin(User $admin)
    {
        return $this->deleteKasir($admin);
    }

    // View all bookings
    public function allBookings()
    {
        $bookings = ServiceBooking::with('user', 'service')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('manager.bookings', compact('bookings'));
    }
}

