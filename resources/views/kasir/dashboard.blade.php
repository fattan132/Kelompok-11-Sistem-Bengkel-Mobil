@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="font-size: 24px;">Dashboard Kasir</h4>
            <p class="text-secondary mb-0" style="font-size: 13px;">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
        <div>
            <span class="text-secondary" style="font-size: 13px;">{{ now()->format('d M, Y') }}</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stats-card">
                <div class="stats-icon" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-label">Booking Hari Ini</div>
                <div class="stats-value">{{ $todayBookings }}</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="stats-card">
                <div class="stats-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-label">Menunggu Konfirmasi</div>
                <div class="stats-value">{{ $pendingBookings }}</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stats-card">
                <div class="stats-icon" style="background-color: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="stats-label">Sedang Dikerjakan</div>
                <div class="stats-value">{{ $ongoingBookings }}</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stats-card">
                <div class="stats-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-label">Total Customer</div>
                <div class="stats-value">{{ $totalCustomers }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <h5 class="fw-semibold mb-3" style="font-size: 16px;">Menu Cepat</h5>
        </div>
        <div class="col-md-4">
            <a href="{{ route('kasir.bookings.create') }}" class="btn btn-primary w-100 text-start" style="padding: 16px;">
                <i class="fas fa-plus-circle me-2"></i>
                <span class="fw-semibold">Buat Booking Baru</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('kasir.bookings') }}" class="btn btn-outline-primary w-100 text-start" style="padding: 16px;">
                <i class="fas fa-list me-2"></i>
                <span class="fw-semibold">Lihat Semua Booking</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('kasir.users.create') }}" class="btn btn-outline-primary w-100 text-start" style="padding: 16px;">
                <i class="fas fa-user-plus me-2"></i>
                <span class="fw-semibold">Tambah Customer</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('kasir.services') }}" class="btn btn-outline-success w-100 text-start" style="padding: 16px;">
                <i class="fas fa-cogs me-2"></i>
                <span class="fw-semibold">Kelola Layanan</span>
            </a>
        </div>
    </div>
                Tambah Customer
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('kasir.users') }}" class="btn btn-secondary btn-lg w-100">
                <i class="fas fa-users fa-2x mb-2 d-block"></i>
                Kelola Customer
            </a>
        </div>
    </div>

    <!-- Booking Terbaru -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Booking Terbaru</h5>
                    <a href="{{ route('kasir.bookings') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Booking</th>
                                        <th>Customer</th>
                                        <th>Layanan</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                        <tr>
                                            <td><strong>#{{ $booking->id }}</strong></td>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>{{ $booking->service->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                                            <td>
                                                @if($booking->status == 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif($booking->status == 'confirmed')
                                                    <span class="badge bg-info">Dikonfirmasi</span>
                                                @elseif($booking->status == 'ongoing')
                                                    <span class="badge bg-primary">Dikerjakan</span>
                                                @elseif($booking->status == 'completed')
                                                    <span class="badge bg-success">Selesai</span>
                                                @else
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($booking->payment_status == 'paid')
                                                    <span class="badge bg-success">Lunas</span>
                                                @elseif($booking->payment_status == 'unpaid')
                                                    <span class="badge bg-danger">Belum Bayar</span>
                                                @else
                                                    <span class="badge bg-secondary">Refund</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('kasir.bookings.detail', $booking->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada booking hari ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
