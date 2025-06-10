@extends('layouts.app')

@section('title', 'Tambah Pengiriman Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-plus-circle me-2"></i>Tambah Pengiriman Baru</h4>
    <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Pengiriman Jamu Madura</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('shipments.store') }}" method="POST">
            @csrf

            <!-- Informasi Utama -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="shipment_code" class="form-label">Kode Pengiriman <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('shipment_code') is-invalid @enderror"
                               id="shipment_code" name="shipment_code" value="{{ old('shipment_code') }}"
                               placeholder="Contoh: SHP-JM-001" required>
                        @error('shipment_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order_date" class="form-label">Tanggal Order <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('order_date') is-invalid @enderror"
                               id="order_date" name="order_date" value="{{ old('order_date') }}" required>
                        @error('order_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="distributor_id" class="form-label">Distributor <span class="text-danger">*</span></label>
                        <select class="form-select @error('distributor_id') is-invalid @enderror"
                                id="distributor_id" name="distributor_id" required>
                            <option value="">Pilih Distributor</option>
                            @foreach($distributors as $distributor)
                                <option value="{{ $distributor->id }}" {{ old('distributor_id') == $distributor->id ? 'selected' : '' }}>
                                    {{ $distributor->name }} - {{ $distributor->city }}
                                </option>
                            @endforeach
                        </select>
                        @error('distributor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="delivery_schedule_id" class="form-label">Jadwal Pengiriman <span class="text-danger">*</span></label>
                        <select class="form-select @error('delivery_schedule_id') is-invalid @enderror"
                                id="delivery_schedule_id" name="delivery_schedule_id" required>
                            <option value="">Pilih Jadwal</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}" {{ old('delivery_schedule_id') == $schedule->id ? 'selected' : '' }}>
                                    {{ $schedule->route_name }} - {{ $schedule->delivery_date->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('delivery_schedule_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="special_instructions" class="form-label">Instruksi Khusus</label>
                        <textarea class="form-control" id="special_instructions" name="special_instructions"
                                  rows="2" placeholder="Catatan khusus untuk pengiriman">{{ old('special_instructions') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Daftar Produk -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Daftar Produk Jamu</h6>
                    <button type="button" class="btn btn-primary btn-sm" id="addProduct">
                        <i class="fas fa-plus me-1"></i>Tambah Produk
                    </button>
                </div>
                <div class="card-body">
                    <div id="productList">
                        <div class="product-item mb-3 p-3 border rounded">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Produk Jamu <span class="text-danger">*</span></label>
                                    <select class="form-select" name="items[0][product_id]" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-weight="{{ $product->weight }}" data-price="{{ $product->price }}">
                                                {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control quantity-input" name="items[0][quantity]"
                                           min="1" placeholder="0" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Catatan</label>
                                    <input type="text" class="form-control" name="items[0][notes]"
                                           placeholder="Catatan produk">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-product" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('items')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Ringkasan Pengiriman</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5 id="totalItems" class="text-primary">0</h5>
                                <small class="text-muted">Total Item</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5 id="totalWeight" class="text-warning">0 kg</h5>
                                <small class="text-muted">Total Berat</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5 id="totalValue" class="text-success">Rp 0</h5>
                                <small class="text-muted">Total Nilai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Pengiriman
                </button>
                <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let productIndex = 1;

document.getElementById('addProduct').addEventListener('click', function() {
    const productList = document.getElementById('productList');
    const newProduct = `
        <div class="product-item mb-3 p-3 border rounded">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Produk Jamu <span class="text-danger">*</span></label>
                    <select class="form-select" name="items[${productIndex}][product_id]" required>
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-weight="{{ $product->weight }}" data-price="{{ $product->price }}">
                                {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control quantity-input" name="items[${productIndex}][quantity]"
                           min="1" placeholder="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Catatan</label>
                    <input type="text" class="form-control" name="items[${productIndex}][notes]"
                           placeholder="Catatan produk">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-product">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    productList.insertAdjacentHTML('beforeend', newProduct);
    productIndex++;
    updateRemoveButtons();
    attachEventListeners();
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-product') || e.target.parentElement.classList.contains('remove-product')) {
        e.target.closest('.product-item').remove();
        updateRemoveButtons();
        calculateTotals();
    }
});

function updateRemoveButtons() {
    const removeButtons = document.querySelectorAll('.remove-product');
    removeButtons.forEach(button => {
        button.disabled = removeButtons.length === 1;
    });
}

function attachEventListeners() {
    document.querySelectorAll('select[name*="[product_id]"], input[name*="[quantity]"]').forEach(element => {
        element.addEventListener('change', calculateTotals);
    });
}

function calculateTotals() {
    let totalItems = 0;
    let totalWeight = 0;
    let totalValue = 0;

    document.querySelectorAll('.product-item').forEach(item => {
        const productSelect = item.querySelector('select[name*="[product_id]"]');
        const quantityInput = item.querySelector('input[name*="[quantity]"]');

        if (productSelect.value && quantityInput.value) {
            const selectedOption = productSelect.selectedOptions[0];
            const weight = parseFloat(selectedOption.dataset.weight) || 0;
            const price = parseFloat(selectedOption.dataset.price) || 0;
            const quantity = parseInt(quantityInput.value) || 0;

            totalItems += quantity;
            totalWeight += weight * quantity;
            totalValue += price * quantity;
        }
    });

    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalWeight').textContent = totalWeight.toFixed(2) + ' kg';
    document.getElementById('totalValue').textContent = 'Rp ' + totalValue.toLocaleString('id-ID');
}

// Initialize event listeners
attachEventListeners();
</script>
@endpush
@endsection
