@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Pemesanan Saya</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($bookings->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Layanan</th>
                    <th>Kendaraan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Poin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->service->name }}</td>
                    <td>{{ $booking->vehicle_model }}<br><small>({{ $booking->vehicle_number }})</small></td>
                    <td>{{ $booking->booking_date->format('d/m/Y') }}<br><small>{{ $booking->booking_time }}</small></td>
                    <td>
                        @if($booking->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($booking->status == 'completed')
                            <span class="badge bg-success">Selesai</span>
                        @else
                            <span class="badge bg-danger">Dibatalkan</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>
                        @if($booking->points_given > 0)
                            <span class="badge bg-primary">+{{ $booking->points_given }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($booking->status == 'pending')
                            <form action="{{ route('user.booking.cancel', $booking) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin membatalkan?')">Batalkan</button>
                            </form>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
    @else
    <div class="alert alert-info">
        Belum ada pemesanan. <a href="{{ route('user.services') }}">Pesan sekarang</a>
    </div>
    @endif
</div>
@endsection
