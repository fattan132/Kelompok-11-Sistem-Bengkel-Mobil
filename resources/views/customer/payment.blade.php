@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-3"><i class="fas fa-credit-card"></i> Pembayaran</h2>
            <p class="text-muted">Pilih metode pembayaran untuk menyelesaikan booking Anda</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-wallet"></i> Pilih Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.payment.confirm', $booking->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="cash" value="cash" required>
                                <label class="form-check-label w-100" for="cash">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                                            <h5 class="mb-0">Tunai (Cash)</h5>
                                            <small class="text-muted">Bayar langsung di tempat</small>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x text-success d-none"></i>
                                    </div>
                                </label>
                            </div>

                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="bank_transfer" value="bank_transfer" required>
                                <label class="form-check-label w-100" for="bank_transfer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-university fa-2x text-primary mb-2"></i>
                                            <h5 class="mb-0">Transfer Bank</h5>
                                            <small class="text-muted">BCA, Mandiri, BNI, BRI</small>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x text-success d-none"></i>
                                    </div>
                                </label>
                            </div>

                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="e_wallet" value="e_wallet" required>
                                <label class="form-check-label w-100" for="e_wallet">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-mobile-alt fa-2x text-warning mb-2"></i>
                                            <h5 class="mb-0">E-Wallet</h5>
                                            <small class="text-muted">GoPay, OVO, Dana, ShopeePay</small>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x text-success d-none"></i>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Setelah konfirmasi pembayaran, Anda akan menerima nomor struk yang dapat dibagikan via WhatsApp.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.bookings') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check"></i> Konfirmasi Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Ringkasan Booking</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-muted">Nomor Booking</h6>
                    <h5 class="mb-3">#{{ $booking->id }}</h5>

                    <h6 class="text-muted">Layanan</h6>
                    @if($booking->items->count())
                        <ul class="mb-3 ps-3">
                            @foreach($booking->items as $item)
                                <li>
                                    <div class="d-flex justify-content-between">
                                        <span>{{ $item->service->name }}</span>
                                        <strong>Rp {{ number_format($item->price, 0, ',', '.') }}</strong>
                                    </div>
                                    <small class="text-muted">{{ $item->service->description }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mb-3">{{ $booking->service->name }}</p>
                    @endif

                    <h6 class="text-muted">Tanggal & Waktu</h6>
                    <p class="mb-3">
                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}<br>
                        {{ $booking->booking_time }}
                    </p>

                    <h6 class="text-muted">Kendaraan</h6>
                    <p class="mb-3">
                        {{ $booking->vehicle_model }}<br>
                        <strong>{{ $booking->vehicle_number }}</strong>
                    </p>

                    <hr>
                    <div class="mb-3 p-3 bg-light rounded">
                        <h6 class="mb-3"><i class="fas fa-calculator"></i> Perhitungan Harga</h6>
                        
                        @php
                            $servicePrice = $booking->items->count() ? $booking->items->sum('price') : ($booking->service->price ?? 0);
                            $serviceFee = $booking->service_fee ?? 0;
                            $subtotal = $servicePrice + $serviceFee;
                            $taxAmount = $subtotal * 0.11;
                            $totalPrice = $subtotal + $taxAmount;
                        @endphp

                        <div class="d-flex justify-content-between mb-2">
                            <span>Layanan:</span>
                            <strong>Rp {{ number_format($servicePrice, 0, ',', '.') }}</strong>
                        </div>

                        @if($serviceFee > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Biaya Jasa:</span>
                                <strong>Rp {{ number_format($serviceFee, 0, ',', '.') }}</strong>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between mb-2">
                            <span>PPN 11%:</span>
                            <strong class="text-primary">Rp {{ number_format($taxAmount, 0, ',', '.') }}</strong>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">TOTAL:</h5>
                            <h5 class="text-success mb-0">Rp {{ number_format($totalPrice, 0, ',', '.') }}</h5>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Poin yang didapat:</span>
                        <span class="badge bg-success">
                            +{{ $booking->items->count() ? $booking->items->sum('points_earned') : $booking->service->points_earned }} Poin
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.form-check .fa-check-circle').forEach(icon => {
            icon.classList.add('d-none');
        });
        if(this.checked) {
            this.parentElement.querySelector('.fa-check-circle').classList.remove('d-none');
        }
    });
});
</script>
@endpush
@endsection
