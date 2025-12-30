@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-3"><i class="fas fa-history"></i> Riwayat Booking Saya</h2>
            <p class="text-muted">Daftar semua booking yang pernah Anda lakukan</p>
        </div>
    </div>

    @if($bookings->count() > 0)
        <div class="row">
            @foreach($bookings as $booking)
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <h5 class="mb-0">#{{ $booking->id }}</h5>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</small>
                                </div>
                                <div class="col-md-3">
                                    @php
                                        $serviceNames = $booking->items->count()
                                            ? $booking->items->pluck('service.name')->join(', ')
                                            : $booking->service->name;
                                    @endphp
                                    <h6 class="mb-1">{{ $serviceNames }}</h6>
                                    <small class="text-muted">{{ $booking->vehicle_model }}</small><br>
                                    <strong>{{ $booking->vehicle_number }}</strong>
                                </div>
                                <div class="col-md-2 text-center">
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
                                <div class="col-md-2 text-center">
                                    @if($booking->payment_status == 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($booking->payment_status == 'unpaid')
                                        <span class="badge bg-danger">Belum Bayar</span>
                                    @else
                                        <span class="badge bg-secondary">Refund</span>
                                    @endif
                                </div>
                                <div class="col-md-2 text-end">
                                    <h5 class="text-primary mb-0">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h5>
                                </div>
                                <div class="col-md-1 text-end">
                                    @if($booking->payment_status == 'unpaid' && $booking->status == 'completed')
                                        <a href="{{ route('customer.payment', $booking->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-credit-card"></i> Bayar
                                        </a>
                                    @elseif($booking->payment_status == 'unpaid')
                                        <button class="btn btn-secondary btn-sm" disabled title="Menunggu servis selesai">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @elseif($booking->payment_status == 'paid' && $booking->receipt_number)
                                        <a href="{{ route('customer.receipt', $booking->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-receipt"></i> Struk
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h4>Belum Ada Booking</h4>
                <p class="text-muted">Anda belum pernah melakukan booking layanan</p>
                <a href="{{ route('customer.services') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Booking Sekarang
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
