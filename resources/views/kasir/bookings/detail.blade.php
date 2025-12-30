@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-3"><i class="fas fa-file-alt"></i> Detail Booking #{{ $booking->id }}</h2>
            <p class="text-muted">Informasi lengkap booking</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Booking</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Customer</h6>
                            <p class="mb-0"><strong>{{ $booking->user->name }}</strong></p>
                            <small>{{ $booking->user->email }}</small><br>
                            <small>{{ $booking->user->phone }}</small>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Kendaraan</h6>
                            <p class="mb-0">{{ $booking->vehicle_model }}</p>
                            <strong>{{ $booking->vehicle_number }}</strong>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Tanggal Booking</h6>
                            <p>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Waktu</h6>
                            <p>{{ $booking->booking_time }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="text-muted">Layanan</h6>
                            @if($booking->items && $booking->items->count() > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($booking->items as $item)
                                        <li class="mb-2">
                                            <strong>{{ $item->service->name }}</strong> - 
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                            <br><small class="text-muted">{{ $item->service->description }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="mb-0"><strong>{{ $booking->service->name }}</strong></p>
                                <small>{{ $booking->service->description }}</small>
                            @endif
                        </div>
                    </div>

                    @if($booking->notes)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6 class="text-muted">Catatan Customer</h6>
                                <p>{{ $booking->notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($booking->mechanic_notes)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6 class="text-muted">Catatan Mekanik</h6>
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-tools"></i> {{ $booking->mechanic_notes }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Status Booking</h6>
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
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Status Pembayaran</h6>
                            @if($booking->payment_status == 'paid')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-danger">Belum Bayar</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Update Booking</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <strong>Terjadi Kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('kasir.bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="services" class="form-label">
                                Pilih Layanan untuk Ditambahkan 
                                <small class="text-muted">(Ctrl+Click atau Shift+Click untuk pilih multiple)</small>
                            </label>
                            <div class="border rounded p-2" style="background-color: #f8f9fa; max-height: 280px; overflow-y: auto;">
                                <div class="list-group list-group-flush">
                                    @foreach($services as $service)
                                        @php
                                            $isSelected = $booking->items && $booking->items->contains('service_id', $service->id);
                                        @endphp
                                        <label class="list-group-item d-flex align-items-center" style="cursor: pointer; padding: 10px 8px;">
                                            <input type="checkbox" 
                                                   class="form-check-input me-2" 
                                                   name="services[]" 
                                                   value="{{ $service->id }}"
                                                   data-fee="{{ $service->getAutoFee() }}"
                                                   data-difficulty="{{ $service->difficulty_level }}"
                                                   onchange="updateFeeInfo()"
                                                   {{ $isSelected ? 'checked' : '' }}>
                                            <div class="flex-grow-1">
                                                <div class="fw-500">{{ $service->name }}</div>
                                                <small class="text-muted">Rp {{ number_format($service->price, 0, ',', '.') }}</small>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <small class="d-block mt-2 text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Centang layanan untuk menambahkannya ke booking ini
                            </small>
                        </div>

                        <div class="mb-3">
                            <div class="alert alert-info" id="feeInfo">
                                <h6 class="mb-2"><i class="fas fa-info-circle"></i> Biaya Jasa Otomatis</h6>
                                <p class="mb-0" id="feeDetails">Pilih layanan untuk melihat biaya jasa otomatis berdasarkan tingkat kesulitan</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mechanic_notes" class="form-label">Catatan Mekanik</label>
                            <textarea class="form-control @error('mechanic_notes') is-invalid @enderror" 
                                      id="mechanic_notes" 
                                      name="mechanic_notes" 
                                      rows="3" 
                                      placeholder="Contoh: Sudah diganti oli dan filter. Ban depan perlu diganti dalam waktu dekat.">{{ old('mechanic_notes', $booking->mechanic_notes) }}</textarea>
                            @error('mechanic_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="status" class="form-label">Status Booking</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="ongoing" {{ $booking->status == 'ongoing' ? 'selected' : '' }}>Dikerjakan</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($booking->status == 'completed')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <strong>Status Selesai:</strong> Customer sudah bisa melakukan pembayaran.
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kasir.bookings') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if($booking->receipt_number)
                        <h6 class="text-muted">No. Struk</h6>
                        <p class="mb-3"><strong>{{ $booking->receipt_number }}</strong></p>
                    @endif

                    <h6 class="text-muted">Metode Pembayaran</h6>
                    <p class="mb-3">
                        @if($booking->payment_method == 'cash')
                            <span class="badge bg-success">Tunai</span>
                        @elseif($booking->payment_method == 'bank_transfer')
                            <span class="badge bg-primary">Transfer Bank</span>
                        @elseif($booking->payment_method == 'e_wallet')
                            <span class="badge bg-warning">E-Wallet</span>
                        @else
                            <span class="text-muted">Belum dipilih</span>
                        @endif
                    </p>

                    <hr>

                    <div class="mb-3 p-3 bg-light rounded">
                        <h6 class="mb-3"><i class="fas fa-calculator"></i> Perhitungan Harga</h6>
                        
                        @php
                            $servicePrice = 0;
                            if($booking->items && $booking->items->count() > 0) {
                                $servicePrice = $booking->items->sum('price');
                            }
                            $serviceFee = $booking->service_fee ?? 0;
                            $subtotal = $servicePrice + $serviceFee;
                            $taxAmount = $subtotal * 0.11;
                            $totalPrice = $subtotal + $taxAmount;
                        @endphp

                        <div class="d-flex justify-content-between mb-2">
                            <span>Layanan:</span>
                            <strong>Rp {{ number_format($servicePrice, 0, ',', '.') }}</strong>
                        </div>

                        @if($serviceFee > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Biaya Jasa:</span>
                                <strong>Rp {{ number_format($serviceFee, 0, ',', '.') }}</strong>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between mb-2">
                            <span>PPN 11%:</span>
                            <strong class="text-primary">Rp {{ number_format($taxAmount, 0, ',', '.') }}</strong>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">TOTAL:</h5>
                            <h5 class="text-success mb-0">Rp {{ number_format($totalPrice, 0, ',', '.') }}</h5>
                        </div>
                    </div>

                    @if($booking->payment_status == 'paid')
                        <a href="{{ route('kasir.bookings.print', $booking->id) }}" 
                           class="btn btn-success w-100 mt-3" target="_blank">
                            <i class="fas fa-print"></i> Cetak Struk
                        </a>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Aksi Cepat</h6>
                    <form action="{{ route('kasir.bookings.delete', $booking->id) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Hapus Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFeeInfo() {
    // Get all checked services
    const checkboxes = document.querySelectorAll('input[name="services[]"]:checked');
    
    let totalFee = 0;
    let feeDetails = '';
    
    if (checkboxes.length === 0) {
        feeDetails = 'Pilih layanan untuk melihat biaya jasa otomatis berdasarkan tingkat kesulitan';
    } else {
        checkboxes.forEach(checkbox => {
            const fee = parseInt(checkbox.getAttribute('data-fee'));
            const difficulty = checkbox.getAttribute('data-difficulty');
            const label = checkbox.parentElement.textContent.trim();
            const name = label.split('\n')[0].trim();
            
            totalFee += fee;
            
            let difficultyLabel = '';
            if (difficulty === 'hard') {
                difficultyLabel = '<span class="badge bg-danger">Sulit</span>';
            } else if (difficulty === 'easy') {
                difficultyLabel = '<span class="badge bg-success">Mudah</span>';
            } else if (difficulty === 'custom') {
                difficultyLabel = '<span class="badge bg-secondary">Khusus</span>';
            }
            
            feeDetails += '<div class="mb-2">' + name + ' ' + difficultyLabel + '<br><small>Biaya: Rp ' + new Intl.NumberFormat('id-ID').format(fee) + '</small></div>';
        });
        
        feeDetails += '<hr><div class="mt-2"><strong>Total Biaya Jasa: Rp ' + new Intl.NumberFormat('id-ID').format(totalFee) + '</strong></div>';
    }
    
    document.getElementById('feeDetails').innerHTML = feeDetails;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateFeeInfo();
});
</script>
@endsection
