@extends('layouts.app')

@section('title', 'Kelola Layanan')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-cogs"></i> Kelola Layanan</h2>
                    <p class="text-muted">Daftar semua layanan servis yang tersedia</p>
                </div>
                <a href="{{ route('kasir.services.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Layanan Baru
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Layanan</h5>
        </div>
        <div class="card-body">
            @if($services->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Layanan</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Poin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                                <tr>
                                    <td><strong>#{{ $service->id }}</strong></td>
                                    <td>
                                        <i class="fas fa-wrench text-primary"></i>
                                        <strong>{{ $service->name }}</strong>
                                    </td>
                                    <td>{{ Str::limit($service->description, 50) }}</td>
                                    <td><strong class="text-success">Rp {{ number_format($service->price, 0, ',', '.') }}</strong></td>
                                    <td><span class="badge bg-warning">{{ $service->points_earned }} Poin</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('kasir.services.edit', $service->id) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('kasir.services.delete', $service->id) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $services->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada layanan yang tersedia</p>
                    <a href="{{ route('kasir.services.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Layanan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
