<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk Booking #{{ $booking->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0 12mm; }
        }
        .receipt-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }
        .title { font-size: 24px; font-weight: bold; }
        .info-table td { padding: 4px 0; }
    </style>
</head>
<body>
<div class="receipt-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="title">Servis Mobil Online</div>
            <div>Jl. Raya Servis No. 123</div>
            <div>Telp: (021) 1234-5678</div>
        </div>
        <div class="text-end">
            <h5 class="mb-1">Struk #{{ $booking->receipt_number ?? ('INV-' . $booking->id) }}</h5>
            <small>{{ now()->format('d M Y H:i') }}</small>
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <div class="col-md-6">
            <h6>Customer</h6>
            <table class="info-table">
                <tr><td>Nama</td><td>: {{ $booking->user->name }}</td></tr>
                <tr><td>Email</td><td>: {{ $booking->user->email }}</td></tr>
                <tr><td>Telepon</td><td>: {{ $booking->user->phone }}</td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <h6>Kendaraan</h6>
            <table class="info-table">
                <tr><td>Model</td><td>: {{ $booking->vehicle_model }}</td></tr>
                <tr><td>Plat</td><td>: {{ $booking->vehicle_number }}</td></tr>
                <tr><td>Tanggal</td><td>: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }} {{ $booking->booking_time }}</td></tr>
            </table>
        </div>
    </div>

    <h6>Layanan</h6>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Layanan</th>
                <th class="text-end">Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $booking->service->name }}</strong><br>
                    <small class="text-muted">{{ $booking->service->description }}</small>
                </td>
                <td class="text-end">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end">Total</th>
                <th class="text-end">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="row mb-3">
        <div class="col-md-6">
            <h6>Pembayaran</h6>
            <table class="info-table">
                <tr><td>Metode</td><td>:
                    @if($booking->payment_method == 'cash') Tunai
                    @elseif($booking->payment_method == 'bank_transfer') Transfer Bank
                    @elseif($booking->payment_method == 'e_wallet') E-Wallet
                    @else - @endif
                </td></tr>
                <tr><td>Status</td><td>:
                    @if($booking->payment_status == 'paid') LUNAS @else BELUM BAYAR @endif
                </td></tr>
            </table>
        </div>
        <div class="col-md-6 text-end">
            <h6 class="text-success">Terima kasih!</h6>
            <small>Simpan struk ini sebagai bukti pembayaran.</small>
        </div>
    </div>

    <div class="text-center no-print">
        <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
        <a href="{{ route('kasir.bookings') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
