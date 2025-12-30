@extends('layouts.app')

@section('title', 'Kelola Booking')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-calendar-alt"></i> Kelola Booking</h2>
                    <p class="text-muted">Daftar semua booking dari customer</p>
                </div>
                <a href="{{ route('kasir.bookings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Booking Baru
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kasir.bookings') }}" method="GET" class="row g-3" id="filterForm">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Dikerjakan</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="payment_status" class="form-label">Status Pembayaran</label>
                    <select class="form-select" id="payment_status" name="payment_status" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Booking</h5>
        </div>
        <div class="card-body">
            @if($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Layanan</th>
                                <th>Kendaraan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td><strong>#{{ $booking->id }}</strong></td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->service->name }}</td>
                                    <td>
                                        {{ $booking->vehicle_model }}<br>
                                        <strong>{{ $booking->vehicle_number }}</strong>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}<br>{{ $booking->booking_time }}</td>
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
                                        @else
                                            <span class="badge bg-danger">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td><strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('kasir.bookings.detail', $booking->id) }}" 
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($booking->payment_status == 'paid')
                                                <a href="{{ route('kasir.bookings.print', $booking->id) }}" 
                                                   class="btn btn-sm btn-success" title="Print Struk" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada booking</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
