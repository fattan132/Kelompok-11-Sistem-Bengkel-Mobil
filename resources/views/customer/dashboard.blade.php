@extends('layouts.app')

@section('title', 'Servis Mobil - Layanan Terpercaya')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Servis Mobil Berkualitas</h1>
                <p class="lead mb-4">Layanan servis mobil profesional dengan teknisi berpengalaman. Booking mudah, harga transparan, dan garansi kepuasan.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('customer.services') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-wrench me-2"></i>Lihat Layanan
                    </a>
                    <a href="{{ route('customer.bookings') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-calendar me-2"></i>Riwayat Booking
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/car-service-hero.png') }}" alt="Servis Mobil" class="img-fluid" style="max-height: 400px;" onerror="this.src='https://via.placeholder.com/500x300/007bff/ffffff?text=Car+Service'">
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-3">Layanan Unggulan Kami</h2>
                <p class="text-muted">Pilih layanan servis yang sesuai dengan kebutuhan kendaraan Anda</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Service Cards will be populated by JavaScript or we can add static ones -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-3">
                            <i class="fas fa-oil-can fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title fw-bold">Ganti Oli</h5>
                        <p class="card-text text-muted">Servis ganti oli berkala untuk menjaga performa mesin kendaraan Anda.</p>
                        <div class="mt-3">
                            <span class="badge bg-primary mb-2">Mulai dari Rp 50.000</span>
                        </div>
                        <a href="{{ route('customer.services') }}" class="btn btn-primary">Pesan Sekarang</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-3">
                            <i class="fas fa-tools fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">Tune Up Mesin</h5>
                        <p class="card-text text-muted">Perawatan komprehensif mesin untuk performa optimal dan efisiensi bahan bakar.</p>
                        <div class="mt-3">
                            <span class="badge bg-success mb-2">Mulai dari Rp 150.000</span>
                        </div>
                        <a href="{{ route('customer.services') }}" class="btn btn-success">Pesan Sekarang</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-3">
                            <i class="fas fa-car-battery fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title fw-bold">Servis Aki</h5>
                        <p class="card-text text-muted">Pengecekan dan penggantian aki mobil untuk menghindari mogok mendadak.</p>
                        <div class="mt-3">
                            <span class="badge bg-warning text-dark mb-2">Mulai dari Rp 75.000</span>
                        </div>
                        <a href="{{ route('customer.services') }}" class="btn btn-warning">Pesan Sekarang</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-3">
                            <i class="fas fa-tire fa-3x text-danger"></i>
                        </div>
                        <h5 class="card-title fw-bold">Ganti Ban</h5>
                        <p class="card-text text-muted">Penggantian ban dengan kualitas terbaik untuk keselamatan berkendara.</p>
                        <div class="mt-3">
                            <span class="badge bg-danger mb-2">Mulai dari Rp 200.000</span>
                        </div>
                        <a href="{{ route('customer.services') }}" class="btn btn-danger">Pesan Sekarang</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-3">
                            <i class="fas fa-wind fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title fw-bold">Servis AC</h5>
                        <p class="card-text text-muted">Perawatan sistem pendingin mobil untuk kenyamanan selama perjalanan.</p>
                        <div class="mt-3">
                            <span class="badge bg-info mb-2">Mulai dari Rp 100.000</span>
                        </div>
                        <a href="{{ route('customer.services') }}" class="btn btn-info">Pesan Sekarang</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-3">
                            <i class="fas fa-cogs fa-3x text-secondary"></i>
                        </div>
                        <h5 class="card-title fw-bold">Servis Lengkap</h5>
                        <p class="card-text text-muted">Paket servis komprehensif untuk perawatan menyeluruh kendaraan Anda.</p>
                        <div class="mt-3">
                            <span class="badge bg-secondary mb-2">Mulai dari Rp 300.000</span>
                        </div>
                        <a href="{{ route('customer.services') }}" class="btn btn-secondary">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('customer.services') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-list me-2"></i>Lihat Semua Layanan
            </a>
        </div>
    </div>
</section>

<!-- Stats Section (Optional, keep some dashboard feel) -->
@if($totalBookings > 0)
<section class="stats-section py-4">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="stat-item">
                    <h3 class="text-primary fw-bold">{{ $totalBookings }}</h3>
                    <p class="text-muted mb-0">Total Booking</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <h3 class="text-success fw-bold">{{ $activeBookings }}</h3>
                    <p class="text-muted mb-0">Booking Aktif</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <h3 class="text-warning fw-bold">{{ auth()->user()->points }}</h3>
                    <p class="text-muted mb-0">Poin Rewards</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Recent Bookings Section (if any) -->
@if($recentBookings->count() > 0)
<section class="recent-bookings py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center fw-bold mb-4">Booking Terbaru Anda</h3>
                <div class="row g-3">
                    @foreach($recentBookings->take(3) as $booking)
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">#{{ $booking->id }}</h6>
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
                                    </div>
                                    <p class="text-muted mb-1">{{ $booking->service->name }}</p>
                                    <p class="text-muted small mb-2">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                                    <p class="fw-bold text-primary mb-0">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('customer.bookings') }}" class="btn btn-outline-primary">Lihat Semua Booking</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%);
    min-height: 60vh;
    display: flex;
    align-items: center;
}

.service-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.service-icon {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.05);
    border-radius: 50%;
    width: 80px;
    margin: 0 auto;
}

.stat-item {
    padding: 20px;
}

.stats-section {
    background: white;
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
}

@media (max-width: 768px) {
    .hero-section {
        text-align: center;
        padding: 3rem 0;
    }
    
    .service-card {
        margin-bottom: 20px;
    }
}
</style>
@endpush
