@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <a href="{{ route('user.services') }}" class="btn btn-secondary mb-3">Kembali</a>
            <h1 class="mb-4">Pesan Layanan: {{ $service->name }}</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Deskripsi:</strong> {{ $service->description }}</p>
                    <p><strong>Harga:</strong> Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                    <p><strong>Poin yang Diperoleh:</strong> {{ $service->points_earned }} poin</p>
                </div>
            </div>

            <form action="{{ route('user.booking.store', $service) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="vehicle_model" class="form-label">Merek dan Model Kendaraan</label>
                    <input type="text" class="form-control @error('vehicle_model') is-invalid @enderror" id="vehicle_model" name="vehicle_model" placeholder="Contoh: Toyota Avanza" value="{{ old('vehicle_model') }}" required>
                    @error('vehicle_model')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="vehicle_number" class="form-label">Nomor Plat Kendaraan</label>
                    <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" id="vehicle_number" name="vehicle_number" placeholder="Contoh: B 1234 ABC" value="{{ old('vehicle_number') }}" required>
                    @error('vehicle_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="booking_date" class="form-label">Tanggal Pemesanan</label>
                    <input type="date" class="form-control @error('booking_date') is-invalid @enderror" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" required>
                    @error('booking_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="booking_time" class="form-label">Jam Pemesanan</label>
                    <input type="time" class="form-control @error('booking_time') is-invalid @enderror" id="booking_time" name="booking_time" value="{{ old('booking_time') }}" required>
                    @error('booking_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan Tambahan</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Masukkan catatan jika ada">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
                    <a href="{{ route('user.services') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
