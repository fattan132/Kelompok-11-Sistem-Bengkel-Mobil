@extends('layouts.app')

@section('title', 'Dashboard Manager')

@section('content')
<div class="container">
    <div class="row mb-5">
        <div class="col-md-12">
            <h1 class="fw-bold mb-2" style="font-size: 2.5rem;"><i class="fas fa-chart-line" style="color: #3b82f6;"></i> Dashboard Manager</h1>
            <p style="font-size: 1.1rem;">Selamat datang, <strong>{{ auth()->user()->name }}</strong>!</p>
        </div>
    </div>

    <!-- Statistik Cards dengan Gradien Modern -->
    <div class="row mb-5 g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 h-100" style="background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%); color: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="card-title mb-3 fw-bold opacity-9">Pendapatan Hari Ini</h6>
                            <h3 class="mb-2 fw-bold" style="font-size: 1.5rem;">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave fa-3x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 h-100" style="background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%); color: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="card-title mb-3 fw-bold opacity-9">Pendapatan Bulan Ini</h6>
                            <h3 class="mb-2 fw-bold" style="font-size: 1.5rem;">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt fa-3x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 h-100" style="background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%); color: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="card-title mb-3 fw-bold opacity-9">Total Transaksi</h6>
                            <h3 class="mb-2 fw-bold" style="font-size: 2rem;">{{ $totalTransactions }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-receipt fa-3x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 h-100" style="background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%); color: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="card-title mb-3 fw-bold opacity-9">Total Customer</h6>
                            <h3 class="mb-2 fw-bold" style="font-size: 2rem;">{{ $totalCustomers }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Aksi Cepat -->
    <div class="row mb-5">
        <div class="col-md-12">
            <h4 class="fw-bold mb-4">Aksi Cepat</h4>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('manager.sales-report') }}" class="btn btn-primary btn-lg w-100 py-4" style="font-size: 1rem;">
                <i class="fas fa-chart-bar fa-2x mb-3 d-block"></i>
                <strong>Laporan Penjualan</strong>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('manager.kasir.create') }}" class="btn btn-primary btn-lg w-100 py-4" style="font-size: 1rem; background-color: #10b981;">
                <i class="fas fa-user-plus fa-2x mb-3 d-block"></i>
                <strong>Tambah Kasir Baru</strong>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('manager.kasir') }}" class="btn btn-primary btn-lg w-100 py-4" style="font-size: 1rem; background-color: #06b6d4;">
                <i class="fas fa-users-cog fa-2x mb-3 d-block"></i>
                <strong>
                Kelola Kasir
            </a>
        </div>
    </div>

    <!-- Grafik dan Laporan -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-area"></i> Pendapatan 7 Hari Terakhir</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-trophy"></i> Layanan Terpopuler</h5>
                </div>
                <div class="card-body">
                    @if($topServices->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($topServices as $service)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $service->service->name }}
                                    <span class="badge bg-primary rounded-pill">{{ $service->total }} x</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted text-center">Belum ada data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Transaksi Terbaru</h5>
                    <a href="{{ route('manager.sales-report') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Struk</th>
                                        <th>Customer</th>
                                        <th>Layanan</th>
                                        <th>Tanggal</th>
                                        <th>Metode Bayar</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td><strong>{{ $transaction->receipt_number }}</strong></td>
                                            <td>{{ $transaction->user->name }}</td>
                                            <td>{{ $transaction->service->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->booking_date)->format('d M Y') }}</td>
                                            <td>
                                                @if($transaction->payment_method == 'cash')
                                                    <span class="badge bg-success">Tunai</span>
                                                @elseif($transaction->payment_method == 'bank_transfer')
                                                    <span class="badge bg-info">Transfer Bank</span>
                                                @else
                                                    <span class="badge bg-warning">E-Wallet</span>
                                                @endif
                                            </td>
                                            <td><strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></td>
                                            <td>
                                                @if($transaction->payment_status == 'paid')
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-danger">Belum Bayar</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk chart
    const chartData = @json($chartData);
    
    // Create chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: chartData.data,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
