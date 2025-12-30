@extends('layouts.app')

@section('title', 'Edit Layanan')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-3"><i class="fas fa-edit"></i> Edit Layanan</h2>
            <p class="text-muted">Ubah informasi layanan servis</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Form Edit Layanan</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Terjadi kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('kasir.services.update', $service->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $service->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      required>{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', $service->price) }}" 
                                           min="0"
                                           step="1000"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="points_earned" class="form-label">Poin yang Didapat <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('points_earned') is-invalid @enderror" 
                                           id="points_earned" 
                                           name="points_earned" 
                                           value="{{ old('points_earned', $service->points_earned) }}" 
                                           min="0"
                                           required>
                                    @error('points_earned')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="difficulty_level" class="form-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('difficulty_level') is-invalid @enderror" 
                                            id="difficulty_level" 
                                            name="difficulty_level"
                                            required>
                                        <option value="">-- Pilih Tingkat Kesulitan --</option>
                                        <option value="easy" {{ old('difficulty_level', $service->difficulty_level) == 'easy' ? 'selected' : '' }}>
                                            Mudah (Biaya: Rp 100.000)
                                        </option>
                                        <option value="hard" {{ old('difficulty_level', $service->difficulty_level) == 'hard' ? 'selected' : '' }}>
                                            Sulit (Biaya: Rp 350.000)
                                        </option>
                                        <option value="custom" {{ old('difficulty_level', $service->difficulty_level) == 'custom' ? 'selected' : '' }}>
                                            Khusus (Tanpa Biaya Jasa)
                                        </option>
                                    </select>
                                    @error('difficulty_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="has_custom_fee" class="form-label">&nbsp;</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="has_custom_fee" name="has_custom_fee" 
                                               value="1" {{ old('has_custom_fee', $service->has_custom_fee) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_custom_fee">
                                            Layanan dengan biaya jasa khusus
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kasir.services') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Layanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
