@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-4">Dashboard Admin</h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Layanan</h5>
                    <h2>{{ $total_services }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total User</h5>
                    <h2>{{ $total_users }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Pemesanan</h5>
                    <h2>{{ $total_bookings }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Pendapatan</h5>
                    <h2>Rp {{ number_format($total_revenue, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- More Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h5 class="card-title">Pemesanan Pending</h5>
                    <h3 class="text-danger">{{ $pending_bookings }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title">Pemesanan Selesai</h5>
                    <h3 class="text-success">{{ $completed_bookings }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Menu Cepat</h5>
                    <a href="{{ route('admin.services') }}" class="btn btn-sm btn-outline-primary me-2">Kelola Layanan</a>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-sm btn-outline-success">Kelola Pemesanan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
