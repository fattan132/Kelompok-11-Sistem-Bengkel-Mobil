@extends('layouts.app')

@section('title', 'Buat Pemesanan - Admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus"></i> Buat Pemesanan Untuk User</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.bookings.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">Pilih User <span class="text-danger">*</span></label>
                                <select class="form-select" id="user_id" name="user_id" required>
                                    <option value="">-- Pilih User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="service_id" class="form-label">Pilih Layanan <span class="text-danger">*</span></label>
                                <select class="form-select" id="service_id" name="service_id" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" 
                                                data-price="{{ $service->price }}"
                                                data-points="{{ $service->points_earned }}"
                                                {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted" id="service-info"></small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="booking_date" class="form-label">Tanggal Pemesanan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="booking_date" name="booking_date" 
                                       value="{{ old('booking_date', date('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <small class="text-muted">Status "Completed" akan langsung memberikan poin ke user</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vehicle_type" class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" 
                                       value="{{ old('vehicle_type') }}" 
                                       placeholder="Contoh: Toyota Avanza" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="vehicle_plate" class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate" 
                                       value="{{ old('vehicle_plate') }}" 
                                       placeholder="Contoh: B 1234 XYZ" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Buat Pemesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('service_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const price = selected.getAttribute('data-price');
    const points = selected.getAttribute('data-points');
    
    if (price && points) {
        document.getElementById('service-info').textContent = 
            `Harga: Rp ${parseInt(price).toLocaleString('id-ID')} | Poin: ${points}`;
    } else {
        document.getElementById('service-info').textContent = '';
    }
});

// Trigger on page load if there's old value
if (document.getElementById('service_id').value) {
    document.getElementById('service_id').dispatchEvent(new Event('change'));
}
</script>
@endpush
