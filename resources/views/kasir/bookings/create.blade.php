@extends('layouts.app')

@section('title', 'Buat Booking Baru')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-1"><i class="fas fa-plus-circle"></i> Buat Booking Baru</h2>
            <p class="text-muted">Kasir dapat membuat booking untuk customer</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Form Booking</h5>
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

                    <form action="{{ route('kasir.bookings.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Pilih Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Layanan <span class="text-danger">*</span></label>
                            <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto;">
                                @foreach($services as $service)
                                <div class="form-check mb-2">
                                    <input class="form-check-input service-checkbox" type="checkbox" 
                                           id="service_{{ $service->id }}" 
                                           name="services[]" 
                                           value="{{ $service->id }}"
                                           data-price="{{ $service->price }}"
                                           data-points="{{ $service->points_earned }}"
                                           data-name="{{ $service->name }}"
                                           {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="service_{{ $service->id }}">
                                        <strong>{{ $service->name }}</strong><br>
                                        <small class="text-muted">Rp {{ number_format($service->price, 0, ',', '.') }} ({{ $service->points_earned }} poin)</small>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('services')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            @error('services.*')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="booking_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('booking_date') is-invalid @enderror" 
                                       id="booking_date" name="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('booking_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="booking_time" class="form-label">Waktu <span class="text-danger">*</span></label>
                                <select class="form-select @error('booking_time') is-invalid @enderror" id="booking_time" name="booking_time" required>
                                    <option value="">Pilih Waktu</option>
                                    @foreach($timeSlots as $slot)
                                        <option value="{{ $slot['time'] }}" 
                                                {{ $slot['is_full'] ? 'disabled' : '' }}
                                                {{ old('booking_time') == $slot['time'] ? 'selected' : '' }}>
                                            {{ $slot['time'] }} 
                                            ({{ $slot['available'] }}/{{ $slot['total'] }} slot)
                                            {{ $slot['is_full'] ? '- PENUH' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Max 4 mobil/jam
                                </small>
                                @error('booking_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="vehicle_model" class="form-label">Model Mobil <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('vehicle_model') is-invalid @enderror" 
                                   id="vehicle_model" name="vehicle_model" value="{{ old('vehicle_model') }}" 
                                   placeholder="Contoh: Toyota Avanza 2020" required>
                            @error('vehicle_model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="vehicle_number" class="form-label">Nomor Plat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" 
                                   id="vehicle_number" name="vehicle_number" value="{{ old('vehicle_number') }}" 
                                   placeholder="Contoh: B 1234 XYZ" required>
                            @error('vehicle_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" placeholder="Catatan khusus">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="cash">Tunai</option>
                                    <option value="bank_transfer">Transfer Bank</option>
                                    <option value="e_wallet">E-Wallet</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="payment_status" class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                                    <option value="unpaid">Belum Bayar</option>
                                    <option value="paid">Lunas</option>
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status Booking <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pending">Menunggu</option>
                                <option value="confirmed">Dikonfirmasi</option>
                                <option value="ongoing">Dikerjakan</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kasir.bookings') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Ringkasan</h5>
                </div>
                <div class="card-body">
                    <div id="service-summary" class="mb-0">
                        <div class="mb-3">
                            <p class="text-muted mb-2 fw-semibold">Layanan Dipilih:</p>
                            <ul id="selected-services" class="list-unstyled mb-3">
                                <li class="text-muted">Belum ada</li>
                            </ul>
                        </div>
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <strong id="subtotal-price">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                                <span>Total Poin:</span>
                                <strong id="total-points">0 Poin</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">TOTAL:</span>
                                <strong class="fw-bold text-primary" style="font-size: 18px;" id="total-price">Rp 0</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const checkboxes = document.querySelectorAll('.service-checkbox');
const selectedServicesEl = document.getElementById('selected-services');
const subtotalEl = document.getElementById('subtotal-price');
const totalPointsEl = document.getElementById('total-points');
const totalPriceEl = document.getElementById('total-price');

function formatRupiah(value) {
    return 'Rp ' + Number(value).toLocaleString('id-ID');
}

function updateSummary() {
    const selected = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => ({
            id: cb.value,
            name: cb.dataset.name,
            price: Number(cb.dataset.price),
            points: Number(cb.dataset.points)
        }));

    if (selected.length === 0) {
        selectedServicesEl.innerHTML = '<li class="text-muted">Belum ada</li>';
        subtotalEl.textContent = 'Rp 0';
        totalPointsEl.textContent = '0 Poin';
        totalPriceEl.textContent = 'Rp 0';
        return;
    }

    const html = selected.map(item => `
        <li class="mb-2">
            <strong>${item.name}</strong><br>
            <small class="text-muted">${formatRupiah(item.price)}</small>
        </li>
    `).join('');
    selectedServicesEl.innerHTML = html;

    const totalPrice = selected.reduce((sum, item) => sum + item.price, 0);
    const totalPoints = selected.reduce((sum, item) => sum + item.points, 0);

    subtotalEl.textContent = formatRupiah(totalPrice);
    totalPointsEl.textContent = totalPoints + ' Poin';
    totalPriceEl.textContent = formatRupiah(totalPrice);
}

checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));
updateSummary();
</script>
@endpush
@endsection
