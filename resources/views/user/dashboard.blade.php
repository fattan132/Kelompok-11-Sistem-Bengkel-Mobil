@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-4">Dashboard User</h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Poin</h5>
                    <h2>{{ $total_points }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Pemesanan</h5>
                    <h2>{{ $total_bookings }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2>{{ $pending_bookings }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Selesai</h5>
                    <h2>{{ $completed_bookings }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Cepat -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Menu Cepat</h5>
                    <a href="{{ route('user.services') }}" class="btn btn-primary me-2">Pesan Layanan</a>
                    <a href="{{ route('user.bookings') }}" class="btn btn-success me-2">Lihat Pemesanan</a>
                    <a href="{{ route('user.profile') }}" class="btn btn-info">Edit Profil</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
