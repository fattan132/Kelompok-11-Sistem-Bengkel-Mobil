@extends('layouts.app')

@section('title', 'Kelola Customer')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-users"></i> Kelola Customer</h2>
                    <p class="text-muted">Daftar semua customer yang terdaftar</p>
                </div>
                <a href="{{ route('kasir.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Tambah Customer Baru
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
            <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Customer</h5>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('kasir.users') }}" method="GET" class="d-flex gap-2">
                        <input type="number" 
                               name="search_id" 
                               class="form-control" 
                               placeholder="Cari berdasarkan ID Customer..." 
                               value="{{ request('search_id') }}"
                               min="1">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        @if(request('search_id'))
                            <a href="{{ route('kasir.users') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>
            
            @if(request('search_id'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Menampilkan hasil pencarian untuk ID: <strong>{{ request('search_id') }}</strong>
                </div>
            @endif
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Poin</th>
                                <th>Total Booking</th>
                                <th>Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td><strong>#{{ $user->id }}</strong></td>
                                    <td>
                                        <i class="fas fa-user text-success"></i>
                                        {{ $user->name }}
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ $user->points }} Poin</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $user->service_bookings_count ?? 0 }} Booking</span>
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('kasir.users.detail', $user->id) }}" 
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('kasir.users.delete', $user->id) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus customer ini?')">
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
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada customer yang terdaftar</p>
                    <a href="{{ route('kasir.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Tambah Customer Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
