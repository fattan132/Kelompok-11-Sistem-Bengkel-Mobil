@extends('layouts.app')

@section('title', 'Layanan Servis')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-3"><i class="fas fa-wrench"></i> Layanan Servis Kami</h2>
            <p class="text-muted">Pilih layanan yang Anda butuhkan dan buat booking sekarang</p>
        </div>
    </div>

    <div class="row">
        @forelse($services as $service)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-wrapper">
                                <i class="fas fa-tools fa-3x text-primary"></i>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success">
                                    <i class="fas fa-star"></i> +{{ $service->points_earned }} Poin
                                </span>
                            </div>
                        </div>
                        <h4 class="card-title">{{ $service->name }}</h4>
                        <p class="card-text text-muted">{{ $service->description }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <h3 class="text-primary mb-0">Rp {{ number_format($service->price, 0, ',', '.') }}</h3>
                            </div>
                            <div>
                                <a href="{{ route('customer.booking.form', $service->id) }}" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus"></i> Booking
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <p class="mb-0">Belum ada layanan yang tersedia saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
}
</style>
@endpush
@endsection
