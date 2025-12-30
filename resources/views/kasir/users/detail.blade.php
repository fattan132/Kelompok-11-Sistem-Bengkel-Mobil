@extends('layouts.app')

@section('title', 'Detail Customer')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-3"><i class="fas fa-user-circle"></i> Detail Customer #{{ $user->id }}</h2>
            <p class="text-muted">Informasi lengkap customer dan riwayat booking</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted small">Nama</h6>
                        <p class="mb-0"><strong>{{ $user->name }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted small">ID</h6>
                        <p class="mb-0"><strong>#{{ $user->id }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted small">Email</h6>
                        <p class="mb-0"><strong>{{ $user->email }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted small">No. Telepon</h6>
                        <p class="mb-0"><strong>{{ $user->phone ?? '-' }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted small">Terdaftar Sejak</h6>
                        <p class="mb-0"><strong>{{ $user->created_at->format('d M Y H:i') }}</strong></p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted small">Poin Reward</h6>
                        <p class="mb-0"><span class="badge bg-warning" style="font-size: 14px;">{{ $user->points }} Poin</span></p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted small">Total Booking</h6>
                        <p class="mb-0"><span class="badge bg-info" style="font-size: 14px;">{{ $bookings->count() }} Booking</span></p>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('kasir.users') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <form action="{{ route('kasir.users.delete', $user->id) }}" method="POST" class="w-100"
                      onsubmit="return confirm('Yakin ingin menghapus customer ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar"></i> Riwayat Booking</h5>
                </div>
                <div class="card-body">
                    @if($bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Booking</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Layanan</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td><strong>#{{ $booking->id }}</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                                            <td>{{ $booking->booking_time }}</td>
                                            <td>
                                                @if($booking->items && $booking->items->count() > 0)
                                                    <small>
                                                        @foreach($booking->items->take(2) as $item)
                                                            <span class="badge bg-light text-dark">{{ $item->service->name }}</span>
                                                        @endforeach
                                                        @if($booking->items->count() > 2)
                                                            <span class="badge bg-light text-dark">+{{ $booking->items->count() - 2 }} lagi</span>
                                                        @endif
                                                    </small>
                                                @else
                                                    <small>{{ $booking->service->name }}</small>
                                                @endif
                                            </td>
                                            <td>
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
                                            </td>
                                            <td><strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></td>
                                            <td>
                                                <a href="{{ route('kasir.bookings.detail', $booking->id) }}" 
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada riwayat booking</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
