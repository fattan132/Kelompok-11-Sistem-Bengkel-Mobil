@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    {{-- HERO SECTION --}}
    <section class="welcome-hero">
        <div class="welcome-overlay"></div>

        <div class="container-fluid">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6">

                    <h1 class="hero-title">
                        Bangun Kepercayaan <br>
                        Melalui Servis Profesional
                    </h1>

                    <p class="hero-subtitle">
                        Platform servis mobil modern dengan sistem booking online,
                        transparan, dan terpercaya.
                    </p>

                    <div class="hero-actions mt-4">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">
                            Mulai Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- INFO STRIP --}}
    <section class="bg-light py-5">
        <div class="container-fluid">
            <div class="row text-center g-4">

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <div class="stats-icon mx-auto mb-3"
                                style="background: rgba(59,130,246,.1); color: var(--primary-blue);">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Layanan Lengkap</h5>
                            <p>Mulai dari perawatan rutin hingga servis lanjutan</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <div class="stats-icon mx-auto mb-3"
                                style="background: rgba(16,185,129,.1); color: var(--success-color);">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Aman & Transparan</h5>
                            <p>Harga jelas, status servis bisa dipantau real-time</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <div class="stats-icon mx-auto mb-3"
                                style="background: rgba(245,158,11,.1); color: var(--warning-color);">
                                <i class="fas fa-gift"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Poin Reward</h5>
                            <p>Dapatkan poin setiap transaksi dan tukar reward</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection