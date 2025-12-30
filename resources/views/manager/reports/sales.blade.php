@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-1"><i class="fas fa-chart-bar"></i> Laporan Penjualan</h2>
            <p class="text-muted">Analisis lengkap transaksi dan pendapatan</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('manager.sales-report') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="period" class="form-label">Periode</label>
                    <select class="form-select" id="period" name="period">
                        <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $start_date }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $end_date }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title mb-0">Total Pendapatan</h6>
                    <h2 class="mt-2 mb-0">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h2>
                    <small>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title mb-0">Total Transaksi</h6>
                    <h2 class="mt-2 mb-0">{{ $total_transactions }}</h2>
                    <small>Transaksi lunas</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title mb-0">Rata-rata Transaksi</h6>
                    <h2 class="mt-2 mb-0">Rp {{ number_format($avg_transaction, 0, ',', '.') }}</h2>
                    <small>Per transaksi</small>
                </div>
            </div>
        </div>
    </div>

    <!-- By Service -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-wrench"></i> Pendapatan per Layanan</h5>
                </div>
                <div class="card-body">
                    @if($by_service->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Layanan</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($by_service as $item)
                                        <tr>
                                            <td>{{ $item['name'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $item['count'] }}x</span>
                                            </td>
                                            <td class="text-end">
                                                <strong>Rp {{ number_format($item['revenue'], 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Belum ada data</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card"></i> Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if($by_payment->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Metode</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($by_payment as $item)
                                        <tr>
                                            <td>
                                                @if($item['method'] == 'cash')
                                                    <i class="fas fa-money-bill text-success"></i> Tunai
                                                @elseif($item['method'] == 'bank_transfer')
                                                    <i class="fas fa-university text-primary"></i> Transfer Bank
                                                @else
                                                    <i class="fas fa-wallet text-warning"></i> E-Wallet
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $item['count'] }}x</span>
                                            </td>
                                            <td class="text-end">
                                                <strong>Rp {{ number_format($item['revenue'], 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Belum ada data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction List -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Detail Transaksi</h5>
        </div>
        <div class="card-body">
            @if($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Struk</th>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Layanan</th>
                                <th>Metode</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td><strong>{{ $booking->receipt_number }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->service->name }}</td>
                                    <td>
                                        @if($booking->payment_method == 'cash')
                                            <span class="badge bg-success">Tunai</span>
                                        @elseif($booking->payment_method == 'bank_transfer')
                                            <span class="badge bg-primary">Transfer</span>
                                        @else
                                            <span class="badge bg-warning">E-Wallet</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-dark">
                                <th colspan="5" class="text-end">TOTAL:</th>
                                <th class="text-end">Rp {{ number_format($total_revenue, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada transaksi pada periode ini</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
