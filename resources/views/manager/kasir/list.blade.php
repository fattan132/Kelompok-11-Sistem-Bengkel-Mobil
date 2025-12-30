@extends('layouts.app')

@section('title', 'Kelola Kasir')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-users-cog"></i> Kelola Kasir</h2>
                    <p class="text-muted">Daftar semua kasir yang terdaftar</p>
                </div>
                <a href="{{ route('manager.kasir.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Kasir Baru
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
            <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Kasir</h5>
        </div>
        <div class="card-body">
            @if($kasirs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Alamat</th>
                                <th>Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kasirs as $kasir)
                                <tr>
                                    <td><strong>#{{ $kasir->id }}</strong></td>
                                    <td>
                                        <i class="fas fa-user-tie text-info"></i>
                                        {{ $kasir->name }}
                                    </td>
                                    <td>{{ $kasir->email }}</td>
                                    <td>{{ $kasir->phone }}</td>
                                    <td>{{ Str::limit($kasir->address, 30) }}</td>
                                    <td>{{ $kasir->created_at->format('d M Y') }}</td>
                                    <td>
                                        <form action="{{ route('manager.kasir.delete', $kasir->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kasir ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $kasirs->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada kasir yang terdaftar</p>
                    <a href="{{ route('manager.kasir.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Kasir Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
