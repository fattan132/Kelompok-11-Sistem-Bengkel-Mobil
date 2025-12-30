@extends('layouts.app')

@section('title', 'Form Booking')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-3"><i class="fas fa-calendar-plus"></i> Form Booking Layanan</h2>
            <p class="text-muted">Isi detail booking untuk layanan <strong>{{ $service->name }}</strong></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Detail Booking</h5>
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

                    <form action="{{ route('customer.booking.store', $service->id) }}" method="POST">
                        @csrf

                        <input type="hidden" name="services[]" value="{{ $service->id }}">

                        <div class="mb-3">
                            <label class="form-label">Layanan yang dipilih <span class="text-danger">*</span></label>
                            <div class="p-3 border rounded bg-light">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="service_main" checked disabled>
                                    <label class="form-check-label" for="service_main">
                                        {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }} ({{ $service->points_earned }} poin)
                                        <span class="badge bg-secondary ms-2">Dipilih dari halaman layanan</span>
                                    </label>
                                </div>

                                <hr>
                                <p class="fw-semibold mb-2">Tambahkan layanan lain:</p>

                                @foreach($services as $srv)
                                    @continue($srv->id === $service->id)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input service-checkbox" type="checkbox" 
                                               id="service_{{ $srv->id }}" name="services[]" value="{{ $srv->id }}"
                                               data-price="{{ $srv->price }}" data-points="{{ $srv->points_earned }}"
                                               data-name="{{ $srv->name }}" data-desc="{{ $srv->description }}"
                                               data-difficulty="{{ $srv->difficulty_level }}" 
                                               data-custom-fee="{{ $srv->has_custom_fee ? 'true' : 'false' }}"
                                               {{ in_array($srv->id, old('services', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="service_{{ $srv->id }}">
                                            {{ $srv->name }} - Rp {{ number_format($srv->price, 0, ',', '.') }} ({{ $srv->points_earned }} poin)
                                        </label>
                                    </div>
                                @endforeach

                                @error('services')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                                @error('services.*')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="booking_date" class="form-label">Tanggal Booking <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('booking_date') is-invalid @enderror" 
                                   id="booking_date" name="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('booking_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="booking_time" class="form-label">Waktu <span class="text-danger">*</span></label>
                            <select class="form-select @error('booking_time') is-invalid @enderror" 
                                    id="booking_time" name="booking_time" required>
                                <option value="">Pilih Waktu</option>
                                @foreach($timeSlots as $slot)
                                    <option value="{{ $slot['time'] }}" 
                                            {{ $slot['is_full'] ? 'disabled' : '' }}
                                            {{ old('booking_time') == $slot['time'] ? 'selected' : '' }}>
                                        {{ $slot['time'] }} WIB 
                                        ({{ $slot['available'] }}/{{ $slot['total'] }} slot tersedia)
                                        {{ $slot['is_full'] ? '- PENUH' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Maksimal 4 mobil per jam
                            </small>
                            @error('booking_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Tambahkan catatan khusus untuk servis Anda">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Voucher Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-ticket-alt me-2"></i>Punya Voucher?
                            </label>
                            
                            @if($myVouchers->count() > 0)
                            <div class="border rounded p-3 bg-light">
                                <p class="text-secondary mb-3" style="font-size: 14px;">
                                    Pilih voucher yang ingin digunakan:
                                </p>
                                
                                <div class="voucher-list">
                                    @foreach($myVouchers as $userVoucher)
                                    <div class="form-check mb-3 p-3 border rounded bg-white voucher-option">
                                        <input class="form-check-input voucher-radio" type="radio" 
                                               name="voucher_code" value="{{ $userVoucher->voucher_code }}" 
                                               id="voucher_{{ $userVoucher->id }}"
                                               data-type="{{ $userVoucher->voucher->type }}"
                                               data-value="{{ $userVoucher->voucher->value }}"
                                               data-free-service-id="{{ $userVoucher->voucher->free_service_id }}">
                                        <label class="form-check-label w-100" for="voucher_{{ $userVoucher->id }}">
                                            <div class="d-flex align-items-start">
                                                <div class="voucher-mini-icon me-3">
                                                    @if($userVoucher->voucher->type == 'discount_percentage')
                                                        <i class="fas fa-percent"></i>
                                                    @elseif($userVoucher->voucher->type == 'discount_fixed')
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    @else
                                                        <i class="fas fa-gift"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold">{{ $userVoucher->voucher->name }}</h6>
                                                    <p class="mb-1 text-secondary" style="font-size: 13px;">
                                                        {{ $userVoucher->voucher->description }}
                                                    </p>
                                                    <small class="text-muted">
                                                        <code>{{ $userVoucher->voucher_code }}</code>
                                                    </small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                               name="voucher_code" value="" 
                                               id="no_voucher" checked>
                                        <label class="form-check-label" for="no_voucher">
                                            Tidak menggunakan voucher
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Anda belum memiliki voucher. 
                                <a href="{{ route('customer.rewards') }}" class="alert-link">Tukar poin Anda</a> 
                                untuk mendapatkan voucher!
                            </div>
                            @endif
                            
                            @error('voucher_code')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.services') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-arrow-right"></i> Lanjut ke Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Ringkasan Layanan</h5>
                </div>
                <div class="card-body">
                    <div id="selected-services" class="mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1">{{ $service->name }}</h6>
                                <small class="text-muted">{{ $service->description }}</small>
                            </div>
                            <strong>Rp {{ number_format($service->price, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal Layanan:</span>
                        <strong id="subtotal-price">Rp {{ number_format($service->price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Jasa:</span>
                        <strong id="service-fee">Rp 0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal + Jasa:</span>
                        <strong id="subtotal-with-fee">Rp {{ number_format($service->price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>PPN 11%:</span>
                        <strong id="tax-amount">Rp 0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Diskon Voucher:</span>
                        <strong id="discount-amount">- Rp 0</strong>
                    </div>
                    <div id="discount-note" class="text-muted small mb-2" style="display:none;"></div>
                    <div class="d-flex justify-content-between mb-2 border-top pt-2">
                        <span>Total Bayar:</span>
                        <strong id="total-price">Rp {{ number_format($service->price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Perkiraan Poin:</span>
                        <span class="badge bg-success" id="total-points">+{{ $service->points_earned }} Poin</span>
                    </div>
                    <div class="text-muted small" id="service-count">1 layanan dipilih</div>
                    <hr>
                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="fas fa-lightbulb"></i> 
                            <strong>Tips:</strong> Tambahkan beberapa layanan sekaligus untuk menghemat waktu servis.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-star"></i> Poin Anda Saat Ini</h6>
                    <h2 class="text-primary">{{ auth()->user()->points }} Poin</h2>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
const baseService = {
    id: Number(@json($service->id)),
    name: @json($service->name),
    desc: @json($service->description),
    price: Number(@json($service->price)),
    points: Number(@json($service->points_earned)),
    difficulty: @json($service->difficulty_level),
    hasCustomFee: Boolean(@json($service->has_custom_fee)),
};

const checkboxes = document.querySelectorAll('.service-checkbox');
const selectedContainer = document.getElementById('selected-services');
const subtotalEl = document.getElementById('subtotal-price');
const serviceFeeEl = document.getElementById('service-fee');
const subtotalWithFeeEl = document.getElementById('subtotal-with-fee');
const taxAmountEl = document.getElementById('tax-amount');
const discountEl = document.getElementById('discount-amount');
const totalPriceEl = document.getElementById('total-price');
const discountNoteEl = document.getElementById('discount-note');
const voucherRadios = document.querySelectorAll('.voucher-radio');
const totalPointsEl = document.getElementById('total-points');
const serviceCountEl = document.getElementById('service-count');

function getAutoFee(difficulty, hasCustomFee) {
    if (hasCustomFee) {
        return 0;
    }
    
    switch(difficulty) {
        case 'hard': return 350000;
        case 'easy': return 100000;
        case 'medium': return 50000;
        default: return 0;
    }
}

function formatRupiah(value) {
    return 'Rp ' + Number(value).toLocaleString('id-ID');
}

function getSelectedVoucher() {
    const checked = document.querySelector('.voucher-radio:checked');
    if (!checked) return null;
    return {
        type: checked.dataset.type,
        value: Number(checked.dataset.value || 0),
        freeServiceId: checked.dataset.freeServiceId ? Number(checked.dataset.freeServiceId) : null,
        code: checked.value
    };
}

function renderSummary() {
    const extras = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => ({
            id: Number(cb.value),
            name: cb.dataset.name,
            desc: cb.dataset.desc,
            price: Number(cb.dataset.price),
            points: Number(cb.dataset.points),
            difficulty: cb.dataset.difficulty || 'medium',
            hasCustomFee: cb.dataset.customFee === 'true'
        }));

    const items = [baseService, ...extras];

    const html = items.map(item => `
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="mb-1">${item.name}</h6>
                <small class="text-muted">${item.desc || '-'}</small>
            </div>
            <strong>${formatRupiah(item.price)}</strong>
        </div>
    `).join('');

    selectedContainer.innerHTML = html;

    // Calculate subtotal from services
    const subtotal = items.reduce((sum, item) => sum + Number(item.price), 0);
    
    // Calculate service fee based on difficulty
    let totalServiceFee = 0;
    items.forEach(item => {
        totalServiceFee += getAutoFee(item.difficulty, item.hasCustomFee);
    });
    
    // Calculate tax on (subtotal + service fee)
    const subtotalWithFee = subtotal + totalServiceFee;
    const tax = Math.round(subtotalWithFee * 0.11);
    
    const totalPoints = items.reduce((sum, item) => sum + Number(item.points), 0);

    // Calculate voucher discount
    let discount = 0;
    let showNote = '';
    const selectedVoucher = getSelectedVoucher();
    if (selectedVoucher) {
        if (selectedVoucher.type === 'discount_percentage') {
            // Discount applied to (subtotal + service fee + tax)
            discount = Math.floor(((subtotal + totalServiceFee + tax) * selectedVoucher.value) / 100);
        } else if (selectedVoucher.type === 'discount_fixed') {
            discount = Math.min(selectedVoucher.value, subtotal + totalServiceFee + tax);
        } else if (selectedVoucher.type === 'free_service' && selectedVoucher.freeServiceId) {
            const target = items.find(it => it.id === selectedVoucher.freeServiceId);
            if (target) {
                // Free service means discount for service price + its fee
                const targetFee = getAutoFee(target.difficulty, target.hasCustomFee);
                discount = target.price + targetFee;
            } else {
                showNote = 'Voucher gratis layanan hanya berlaku jika layanan terkait ditambahkan.';
            }
        }
    }

    const finalTotal = Math.max(0, subtotal + totalServiceFee + tax - discount);

    subtotalEl.textContent = formatRupiah(subtotal);
    serviceFeeEl.textContent = formatRupiah(totalServiceFee);
    subtotalWithFeeEl.textContent = formatRupiah(subtotalWithFee);
    taxAmountEl.textContent = formatRupiah(tax);
    discountEl.textContent = `- ${formatRupiah(discount)}`;
    totalPriceEl.textContent = formatRupiah(finalTotal);
    totalPointsEl.textContent = `+${totalPoints} Poin`;
    serviceCountEl.textContent = `${items.length} layanan dipilih`;

    if (showNote) {
        discountNoteEl.style.display = '';
        discountNoteEl.textContent = showNote;
    } else {
        discountNoteEl.style.display = 'none';
        discountNoteEl.textContent = '';
    }
}

checkboxes.forEach(cb => cb.addEventListener('change', renderSummary));
voucherRadios.forEach(rb => rb.addEventListener('change', renderSummary));
renderSummary();
</script>
@endpush

<style>
.voucher-option {
    cursor: pointer;
    transition: all 0.3s ease;
}

.voucher-option:hover {
    background-color: #f8f9fa !important;
    border-color: #0d6efd !important;
}

.voucher-option:has(.voucher-radio:checked) {
    background-color: #e7f1ff !important;
    border-color: #0d6efd !important;
    border-width: 2px !important;
}

.voucher-mini-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    flex-shrink: 0;
}
</style>
@endsection
