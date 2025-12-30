@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Daftar Layanan Servis</h1>
        </div>
    </div>

    <div class="row">
        @foreach($services as $service)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $service->name }}</h5>
                    <p class="card-text">{{ $service->description }}</p>
                    <p class="mb-2">
                        <strong>Harga:</strong> Rp {{ number_format($service->price, 0, ',', '.') }}
                    </p>
                    <p class="mb-3">
                        <strong>Poin:</strong> <span class="badge bg-info">{{ $service->points_earned }} poin</span>
                    </p>
                    <a href="{{ route('user.booking.form', $service) }}" class="btn btn-primary w-100">Pesan Sekarang</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $services->links() }}
    </div>
</div>
@endsection
