@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('admin.users') }}" class="btn btn-secondary mb-3">Kembali</a>
            <h1>Detail User: {{ $user->name }}</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi User</h5>
                    <p><strong>Nama:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Telepon:</strong> {{ $user->phone ?? '-' }}</p>
                    <p><strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>
                    <p><strong>Total Poin:</strong> <span class="badge bg-primary">{{ $user->points }}</span></p>
                    <p><strong>Bergabung:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-3">Riwayat Pemesanan</h3>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->service->name }}</td>
                            <td>{{ $booking->vehicle_model }} ({{ $booking->vehicle_number }})</td>
                            <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
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
                            <td>{{ $booking->points_given }} poin</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($bookings->count() == 0)
                <div class="alert alert-info">User belum memiliki pemesanan</div>
            @endif

            <div class="mt-3">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
