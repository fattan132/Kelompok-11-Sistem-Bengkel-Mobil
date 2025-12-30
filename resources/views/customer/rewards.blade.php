@extends('layouts.app')

@section('title', 'Poin & Rewards')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="mb-4">
        <h4 class="fw-bold mb-2" style="font-size: 24px;">
            <i class="fas fa-gift me-2"></i>Poin & Rewards
        </h4>
        <p class="text-secondary mb-0" style="font-size: 13px;">
            Lihat poin Anda dan benefit yang bisa didapatkan
        </p>
    </div>

    <!-- Current Points Card -->
    <div class="card shadow-sm border-0 mb-4" style="background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%);">
        <div class="card-body p-4 text-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="{{ $currentTier['icon'] }} fa-3x me-3" style="color: {{ $currentTier['color'] }};"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $currentTier['name'] }} Member</h5>
                            <p class="mb-0 opacity-75" style="font-size: 13px;">Status Member Anda</p>
                        </div>
                    </div>
                    <h1 class="display-3 fw-bold mb-0">{{ number_format($currentPoints) }}</h1>
                    <p class="mb-0 opacity-75">Total Poin Anda</p>
                </div>
                
                <div class="col-md-6 mt-4 mt-md-0">
                    @if($nextTier)
                    <div class="bg-white bg-opacity-10 rounded p-3 backdrop-blur">
                        <p class="mb-2 fw-semibold">Progress ke {{ $nextTier['name'] }}</p>
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ $progressPercentage }}%;" 
                                 aria-valuenow="{{ $progressPercentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <p class="mb-0 mt-2" style="font-size: 13px;">
                            <i class="fas fa-star me-1"></i>
                            Butuh {{ number_format($pointsToNextTier) }} poin lagi untuk naik ke {{ $nextTier['name'] }}
                        </p>
                    </div>
                    @else
                    <div class="bg-white bg-opacity-10 rounded p-3 backdrop-blur">
                        <p class="mb-0 fw-semibold">
                            <i class="fas fa-trophy me-2"></i>
                            Selamat! Anda sudah di tier tertinggi!
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tukar Poin Section -->
    <div class="mb-5">
        <h5 class="fw-bold mb-3">
            <i class="fas fa-ticket-alt me-2"></i>Tukar Poin dengan Voucher
        </h5>
        <p class="text-secondary mb-4" style="font-size: 14px;">
            Tukarkan poin Anda dengan voucher diskon atau gratis servis!
        </p>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-3">
            @forelse($availableVouchers as $voucher)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100 voucher-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="voucher-icon me-3">
                                @if($voucher->type == 'discount_percentage')
                                    <i class="fas fa-percent"></i>
                                @elseif($voucher->type == 'discount_fixed')
                                    <i class="fas fa-money-bill-wave"></i>
                                @else
                                    <i class="fas fa-gift"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $voucher->name }}</h6>
                                <p class="text-secondary mb-0" style="font-size: 13px;">
                                    {{ $voucher->description }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-secondary" style="font-size: 13px;">Harga:</span>
                                <span class="fw-bold text-primary">
                                    <i class="fas fa-star me-1"></i>{{ number_format($voucher->points_required) }} Poin
                                </span>
                            </div>
                            
                            @if(auth()->user()->points >= $voucher->points_required)
                            <form action="{{ route('customer.rewards.redeem') }}" method="POST">
                                @csrf
                                <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                                <button type="submit" class="btn btn-primary w-100 btn-sm">
                                    <i class="fas fa-exchange-alt me-1"></i>Tukar Voucher
                                </button>
                            </form>
                            @else
                            <button class="btn btn-secondary w-100 btn-sm" disabled>
                                <i class="fas fa-lock me-1"></i>Poin Tidak Cukup
                            </button>
                            <small class="text-muted d-block mt-2 text-center" style="font-size: 11px;">
                                Butuh {{ number_format($voucher->points_required - auth()->user()->points) }} poin lagi
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Belum ada voucher yang tersedia saat ini.
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- My Vouchers Section -->
    @if($myVouchers->count() > 0)
    <div class="mb-5">
        <h5 class="fw-bold mb-3">
            <i class="fas fa-wallet me-2"></i>Voucher Saya
        </h5>
        <p class="text-secondary mb-4" style="font-size: 14px;">
            Gunakan voucher ini saat booking layanan servis
        </p>

        <div class="row g-3">
            @foreach($myVouchers as $userVoucher)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-success border-2 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="voucher-icon-success me-3">
                                @if($userVoucher->voucher->type == 'discount_percentage')
                                    <i class="fas fa-percent"></i>
                                @elseif($userVoucher->voucher->type == 'discount_fixed')
                                    <i class="fas fa-money-bill-wave"></i>
                                @else
                                    <i class="fas fa-gift"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $userVoucher->voucher->name }}</h6>
                                <p class="text-secondary mb-0" style="font-size: 13px;">
                                    {{ $userVoucher->voucher->description }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-light rounded p-2 mb-3 text-center">
                            <small class="text-muted d-block" style="font-size: 11px;">Kode Voucher</small>
                            <code class="fw-bold" style="font-size: 14px; color: #198754;">{{ $userVoucher->voucher_code }}</code>
                        </div>

                        <div class="border-top pt-2">
                            <small class="text-muted d-block" style="font-size: 11px;">
                                <i class="fas fa-calendar me-1"></i>Berlaku sampai {{ $userVoucher->voucher->valid_until->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tier Benefits Section -->
    <div class="mb-4">
        <h5 class="fw-bold mb-3">
            <i class="fas fa-layer-group me-2"></i>Benefit Setiap Tier
        </h5>
        <p class="text-secondary mb-4" style="font-size: 14px;">
            Kumpulkan poin dari setiap transaksi servis dan dapatkan berbagai keuntungan!
        </p>
    </div>

    <!-- Tiers Grid -->
    <div class="row g-4">
        @foreach($tiers as $tier)
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100 position-relative {{ $currentTier['name'] == $tier['name'] ? 'border border-3' : '' }}" 
                 style="{{ $currentTier['name'] == $tier['name'] ? 'border-color: ' . $tier['color'] . ' !important;' : '' }}">
                
                @if($currentTier['name'] == $tier['name'])
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge" style="background-color: {{ $tier['color'] }}; color: white;">
                        <i class="fas fa-check-circle me-1"></i>Tier Anda
                    </span>
                </div>
                @endif

                <div class="card-body p-4">
                    <!-- Tier Icon & Name -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="{{ $tier['icon'] }} fa-4x" style="color: {{ $tier['color'] }};"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="color: {{ $tier['color'] }};">{{ $tier['name'] }}</h4>
                        <p class="text-secondary mb-0" style="font-size: 13px;">
                            @if($tier['max_points'] == PHP_INT_MAX)
                                {{ number_format($tier['min_points']) }}+ poin
                            @else
                                {{ number_format($tier['min_points']) }} - {{ number_format($tier['max_points']) }} poin
                            @endif
                        </p>
                    </div>

                    <!-- Benefits List -->
                    <div class="benefits-list">
                        <p class="fw-semibold mb-3 text-dark" style="font-size: 14px;">
                            <i class="fas fa-gift me-2"></i>Benefit:
                        </p>
                        <ul class="list-unstyled mb-0">
                            @foreach($tier['benefits'] as $benefit)
                            <li class="mb-2 d-flex align-items-start">
                                <i class="fas fa-check-circle me-2 mt-1" style="color: {{ $tier['color'] }}; font-size: 12px;"></i>
                                <span style="font-size: 13px;">{{ $benefit }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- How to Earn Points -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%);">
                <div class="card-body p-4 text-white">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-lightbulb me-2"></i>Cara Mendapatkan Poin
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3" style="min-width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-car fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Servis Rutin</h6>
                                    <p class="mb-0 opacity-90" style="font-size: 13px;">
                                        Dapatkan poin setiap kali melakukan servis mobil
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3" style="min-width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-birthday-cake fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Bonus Ulang Tahun</h6>
                                    <p class="mb-0 opacity-90" style="font-size: 13px;">
                                        Bonus poin spesial di bulan ulang tahun Anda
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3" style="min-width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Referral</h6>
                                    <p class="mb-0 opacity-90" style="font-size: 13px;">
                                        Ajak teman dan dapatkan bonus poin ekstra
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4 g-3">
        <div class="col-md-6">
            <a href="{{ route('customer.services') }}" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-calendar-plus me-2"></i>Booking Servis
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('customer.bookings') }}" class="btn btn-outline-primary btn-lg w-100">
                <i class="fas fa-history me-2"></i>Riwayat Servis
            </a>
        </div>
    </div>
</div>

<style>
.backdrop-blur {
    backdrop-filter: blur(10px);
}

.benefits-list {
    min-height: 200px;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
}

.voucher-card {
    position: relative;
    overflow: hidden;
}

.voucher-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%);
}

.voucher-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

.voucher-icon-success {
    width: 50px;
    height: 50px;
    background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}
</style>
@endsection
