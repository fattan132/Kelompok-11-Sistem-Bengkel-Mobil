@extends('layouts.app')

@section('title', 'Struk Pembayaran')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0"><i class="fas fa-check-circle"></i> Pembayaran Berhasil!</h4>
                    </div>
                    <div class="card-body" id="receipt-content">
                        <div class="text-center mb-4">
                            <h2>SERVIS MOBIL ONLINE</h2>
                            <p class="text-muted mb-0">Jl. Raya Servis No. 123</p>
                            <p class="text-muted">Telp: (021) 1234-5678</p>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>No. Struk:</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ $booking->receipt_number }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Tanggal:</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y, H:i') }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Customer:</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ $booking->user->name }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Kendaraan:</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ $booking->vehicle_model }}<br>
                                <strong>{{ $booking->vehicle_number }}</strong>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-12">
                                @if($booking->items->count())
                                    <ul class="list-unstyled mb-0">
                                        @foreach($booking->items as $item)
                                            <li class="d-flex justify-content-between">
                                                <div>
                                                    <strong>{{ $item->service->name }}</strong><br>
                                                    <small class="text-muted">{{ $item->service->description }}</small>
                                                </div>
                                                <strong>Rp {{ number_format($item->price, 0, ',', '.') }}</strong>
                                            </li>
                                            <hr class="my-2">
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $booking->service->name }}</strong><br>
                                            <small class="text-muted">{{ $booking->service->description }}</small>
                                        </div>
                                        <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        @php
                            // Hitung subtotal layanan
                            $subtotal = 0;
                            $serviceFee = 0;
                            
                            if($booking->items->count()) {
                                foreach($booking->items as $item) {
                                    $subtotal += $item->service->price;
                                    $serviceFee += $item->service->getAutoFee();
                                }
                            } else {
                                $subtotal = $booking->service->price;
                                $serviceFee = $booking->service->getAutoFee();
                            }
                            
                            $subtotalWithFee = $subtotal + $serviceFee;
                            $ppn = $subtotalWithFee * 0.11;
                            $totalBeforeDiscount = $subtotalWithFee + $ppn;
                            
                            // Cek jika ada voucher discount
                            $discount = $totalBeforeDiscount - $booking->total_price;
                        @endphp

                        <div class="row mb-2">
                            <div class="col-6">
                                <strong>Subtotal Layanan:</strong>
                            </div>
                            <div class="col-6 text-end">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6">
                                <strong>Biaya Jasa:</strong>
                            </div>
                            <div class="col-6 text-end">
                                Rp {{ number_format($serviceFee, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6">
                                <strong>Subtotal + Jasa:</strong>
                            </div>
                            <div class="col-6 text-end">
                                Rp {{ number_format($subtotalWithFee, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6">
                                <strong>PPN 11%:</strong>
                            </div>
                            <div class="col-6 text-end">
                                Rp {{ number_format($ppn, 0, ',', '.') }}
                            </div>
                        </div>

                        @if($discount > 0)
                        <div class="row mb-2">
                            <div class="col-6">
                                <strong class="text-success">Diskon Voucher:</strong>
                            </div>
                            <div class="col-6 text-end text-success">
                                - Rp {{ number_format($discount, 0, ',', '.') }}
                            </div>
                        </div>
                        @endif

                        <hr>

                        <div class="row mb-3">
                            <div class="col-6">
                                <h5>TOTAL:</h5>
                            </div>
                            <div class="col-6 text-end">
                                <h4 class="text-success">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h4>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Metode Bayar:</strong>
                            </div>
                            <div class="col-6 text-end">
                                @if($booking->payment_method == 'cash')
                                    <span class="badge bg-success">Tunai</span>
                                @elseif($booking->payment_method == 'bank_transfer')
                                    <span class="badge bg-primary">Transfer Bank</span>
                                @else
                                    <span class="badge bg-warning">E-Wallet</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Status:</strong>
                            </div>
                            <div class="col-6 text-end">
                                <span class="badge bg-success">LUNAS</span>
                            </div>
                        </div>

                        <hr>

                        <div class="alert alert-success text-center">
                            <i class="fas fa-gift fa-2x mb-2"></i>
                            <h5>Selamat! Anda mendapat {{ $booking->items->count() ? $booking->items->sum('points_earned') : $booking->service->points_earned }} Poin</h5>
                            <p class="mb-0">Total poin Anda sekarang: <strong>{{ auth()->user()->points }} Poin</strong></p>
                        </div>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                Terima kasih atas kepercayaan Anda!<br>
                                Simpan struk ini sebagai bukti pembayaran
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-home"></i> Ke Dashboard
                            </a>
                            <div>
                                <button onclick="printReceipt()" class="btn btn-info me-2">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                                <a href="{{ route('customer.receipt', $booking->id) }}?share=whatsapp"
                                    class="btn btn-success" target="_blank">
                                    <i class="fab fa-whatsapp"></i> Kirim ke WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function printReceipt() {
                window.print();
            }

            // Redirect ke WhatsApp user (direct ke nomor)
            @if(request('share') == 'whatsapp')
                @php
                    // Konversi 08xxxx -> 628xxxx
                    $phone = preg_replace('/^0/', '62', $booking->user->phone);
                    $serviceList = $booking->items->count()
                        ? $booking->items->pluck('service.name')->join(', ')
                        : $booking->service->name;
                    $pointsTotal = $booking->items->count()
                        ? $booking->items->sum('points_earned')
                        : $booking->service->points_earned;
                    
                    // Hitung breakdown untuk WhatsApp
                    $subtotalWA = 0;
                    $serviceFeeWA = 0;
                    if($booking->items->count()) {
                        foreach($booking->items as $item) {
                            $subtotalWA += $item->service->price;
                            $serviceFeeWA += $item->service->getAutoFee();
                        }
                    } else {
                        $subtotalWA = $booking->service->price;
                        $serviceFeeWA = $booking->service->getAutoFee();
                    }
                    $subtotalWithFeeWA = $subtotalWA + $serviceFeeWA;
                    $ppnWA = $subtotalWithFeeWA * 0.11;
                    $discountWA = ($subtotalWithFeeWA + $ppnWA) - $booking->total_price;
                @endphp

                const receiptText = `*STRUK PEMBAYARAN*\n\n` +
                    `Servis Mobil Online\n` +
                    `No. Struk: {{ $booking->receipt_number }}\n` +
                    `Tanggal: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y, H:i') }}\n\n` +
                    `Customer: {{ $booking->user->name }}\n` +
                    `Kendaraan: {{ $booking->vehicle_model }} ({{ $booking->vehicle_number }})\n\n` +
                    `Layanan: {{ $serviceList }}\n\n` +
                    `Subtotal Layanan: Rp {{ number_format($subtotalWA, 0, ',', '.') }}\n` +
                    `Biaya Jasa: Rp {{ number_format($serviceFeeWA, 0, ',', '.') }}\n` +
                    `Subtotal + Jasa: Rp {{ number_format($subtotalWithFeeWA, 0, ',', '.') }}\n` +
                    `PPN 11%: Rp {{ number_format($ppnWA, 0, ',', '.') }}\n` +
                    @if($discountWA > 0)
                    `Diskon Voucher: - Rp {{ number_format($discountWA, 0, ',', '.') }}\n` +
                    @endif
                    `*Total: Rp {{ number_format($booking->total_price, 0, ',', '.') }}*\n\n` +
                    `Metode: {{ $booking->payment_method == 'cash' ? 'Tunai' : ($booking->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet') }}\n` +
                    `Status: LUNAS ✅\n\n` +
                    `Poin didapat: +{{ $pointsTotal }}\n\n` +
                    `Terima kasih!`;

                const whatsappUrl =
                    `https://wa.me/{{ $phone }}?text=${encodeURIComponent(receiptText)}`;

                window.location.href = whatsappUrl;
            @endif
        </script>
    @endpush


    @push('styles')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                #receipt-content,
                #receipt-content * {
                    visibility: visible;
                }

                #receipt-content {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .btn,
                .card-footer,
                nav,
                footer {
                    display: none !important;
                }
            }
        </style>
    @endpush
@endsection