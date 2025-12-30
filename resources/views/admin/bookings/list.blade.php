@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Daftar Pemesanan</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
                <i class="fas fa-calendar-plus"></i> Buat Pemesanan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Layanan</th>
                    <th>Kendaraan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->service->name }}</td>
                    <td>{{ $booking->vehicle_model }} ({{ $booking->vehicle_number }})</td>
                    <td>{{ $booking->booking_date->format('d/m/Y') }} {{ $booking->booking_time }}</td>
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
                        @if($booking->status == 'pending')
                            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button class="btn btn-sm btn-success" onclick="return confirm('Tandai sebagai selesai?')">Selesai</button>
                            </form>
                            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="cancelled">
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Batalkan pemesanan?')">Batalkan</button>
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
</div>
@endsection
