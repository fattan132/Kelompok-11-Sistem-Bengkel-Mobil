<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Servis Mobil Online')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Poppins Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-card: #ffffff;
            --text-primary: #1a1d29;
            --text-secondary: #1e293b;
            --border-color: #cbd5e1;
            --primary-blue: #3b82f6;
            --shadow-sm: 0 1px 3px rgba(26, 29, 41, 0.08);
            --shadow-md: 0 4px 12px rgba(26, 29, 41, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif !important;
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            font-size: 14px !important;
        }

        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color) !important;
            border-radius: 12px !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .stats-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
        }

        .stats-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .btn {
            border-radius: 10px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
        }

        .btn-primary {
            background-color: var(--primary-blue) !important;
            border: none !important;
        }

        .btn-primary:hover {
            background-color: #1e40af !important;
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            color: var(--primary-blue) !important;
            border: 1px solid var(--border-color) !important;
            background-color: transparent !important;
        }

        .btn-outline-primary:hover {
            background-color: rgba(59, 130, 246, 0.05) !important;
            border-color: var(--primary-blue) !important;
        }

        .navbar {
            background-color: var(--bg-card) !important;
            border-bottom: 1px solid var(--border-color) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .navbar-brand {
            font-weight: 700 !important;
            font-size: 20px !important;
            color: var(--text-primary) !important;
        }

        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500 !important;
            font-size: 14px !important;
        }

        .nav-link:hover {
            color: var(--primary-blue) !important;
        }

        .table {
            font-size: 13px !important;
        }

        .table thead th {
            color: var(--text-secondary) !important;
            font-weight: 600 !important;
            border-bottom: 2px solid var(--border-color) !important;
            background-color: transparent !important;
        }

        .table tbody tr {
            border-bottom: 1px solid var(--border-color) !important;
        }

        .table tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.03) !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: var(--text-primary) !important;
            font-weight: 700 !important;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        .text-secondary {
            color: var(--text-secondary) !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-wrench"></i> Servis Mobil
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if(auth()->user()->isManager())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('manager.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('manager.sales-report') }}">Laporan Penjualan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('manager.kasir') }}">Kelola Kasir</a>
                            </li>
                        @elseif(auth()->user()->isKasir())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kasir.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kasir.bookings') }}">Booking</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kasir.services') }}">Layanan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kasir.users') }}">Customer</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.services') }}">Layanan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.bookings') }}">Booking Saya</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.rewards') }}">
                                    <i class="fas fa-gift"></i> Poin & Rewards
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.profile') }}">
                                    <i class="fas fa-star"></i> Poin: {{ auth()->user()->points }}
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i> {{ auth()->user()->name }}
                                @if(auth()->user()->isManager())
                                    <span class="badge bg-primary ms-2">Manager</span>
                                @elseif(auth()->user()->isKasir())
                                    <span class="badge bg-primary ms-2">Kasir</span>
                                @else
                                    <span class="badge bg-primary ms-2">Customer</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                @if(auth()->user()->isCustomer())
                                    <li><a class="dropdown-item" href="{{ route('customer.profile') }}">Profil</a></li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4 min-vh-100">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer text-center py-5 mt-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="footer-title">
                        <i class="fas fa-wrench me-2"></i> Servis Mobil Online
                    </h5>
                    <p class="footer-text">
                        Solusi terpadu untuk layanan perawatan dan servis kendaraan Anda
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p class="footer-copy">
                        &copy; 2025 Servis Mobil Online. Semua hak dilindungi.
                    </p>
                    <small class="footer-small">
                        Sistem Manajemen Servis Mobil dengan Program Loyalitas Poin
                    </small>
                </div>
            </div>
        </div>
    </footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>

</html>