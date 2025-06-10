@extends('layouts.app')

@section('title', 'Tambah Pengiriman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-plus me-2"></i>Tambah Pengiriman Baru</h4>
    <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('shipments.store') }}" method="POST" id="shipmentForm">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <!-- Informasi Dasar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="distributor_id" class="form-label">Distributor <span class="text-danger">*</span></label>
                                <select class="form-select @error('distributor_id') is-invalid @enderror" id="distributor_id" name="distributor_id" required>
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
                                <label for="order_date" class="form-label">Tanggal Order <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('order_date') is-invalid @enderror"
                                       id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
                                @error('order_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Prioritas</label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
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
                                <label for="contact_person" class="form-label">Person in Charge</label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                       id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                                @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="special_instructions" class="form-label">Instruksi Khusus</label>
                        <textarea class="form-control @error('special_instructions') is-invalid @enderror"
                                  id="special_instructions" name="special_instructions" rows="3">{{ old('special_instructions') }}</textarea>
                        @error('special_instructions')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Item Pengiriman -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-boxes me-2"></i>Item Pengiriman</h5>
                    <button type="button" class="btn btn-success btn-sm" id="addItem">
                        <i class="fas fa-plus me-1"></i>Tambah Item
                    </button>
                </div>
                <div class="card-body">
                    <div id="itemsContainer">
                        <div class="item-row row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Produk <span class="text-danger">*</span></label>
                                <select class="form-select product-select" name="items[0][product_id]" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-weight="{{ $product->weight }}">
                                        {{ $product->name }} - {{ $product->sku }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Qty <span class="text-danger">*</span></label>
                                <input type="number" class="form-control quantity-input" name="items[0][quantity]" min="1" value="1" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Harga Satuan</label>
                                <input type="number" class="form-control unit-price" name="items[0][unit_price]" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Berat (kg)</label>
                                <input type="number" class="form-control item-weight" name="items[0][weight]" step="0.01" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Aksi</label>
                                <button type="button" class="btn btn-danger btn-sm w-100 remove-item" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-calculator me-2"></i>Ringkasan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Total Items:</strong></td>
                            <td id="totalItems">0</td>
                        </tr>
                        <tr>
                            <td><strong>Total Berat:</strong></td>
                            <td id="totalWeight">0.00 kg</td>
                        </tr>
                        <tr>
                            <td><strong>Total Nilai:</strong></td>
                            <td id="totalValue">Rp 0</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- <!-- Informasi Pengiriman -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-truck me-2"></i>Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="courier_id" class="form-label">Kurir</label>
                        <select class="form-select @error('courier_id') is-invalid @enderror" id="courier_id" name="courier_id">
                            <option value="">Pilih Kurir</option>
                            @foreach($couriers as $courier)
                            <option value="{{ $courier->id }}" {{ old('courier_id') == $courier->id ? 'selected' : '' }}>
                                {{ $courier->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('courier_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="service_type" class="form-label">Jenis Layanan</label>
                        <select class="form-select @error('service_type') is-invalid @enderror" id="service_type" name="service_type">
                            <option value="">Pilih Layanan</option>
                            <option value="regular" {{ old('service_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="express" {{ old('service_type') == 'express' ? 'selected' : '' }}>Express</option>
                            <option value="same_day" {{ old('service_type') == 'same_day' ? 'selected' : '' }}>Same Day</option>
                        </select>
                        @error('service_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="estimated_delivery" class="form-label">Estimasi Pengiriman</label>
                        <input type="date" class="form-control @error('estimated_delivery') is-invalid @enderror"
                               id="estimated_delivery" name="estimated_delivery" value="{{ old('estimated_delivery') }}">
                        @error('estimated_delivery')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="delivery_cost" class="form-label">Biaya Pengiriman</label>
                        <input type="number" class="form-control @error('delivery_cost') is-invalid @enderror"
                               id="delivery_cost" name="delivery_cost" value="{{ old('delivery_cost') }}">
                        @error('delivery_cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div> --}}

            <!-- Submit -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Pengiriman
                        </button>
                        <button type="button" class="btn btn-success" onclick="submitWithStatus('confirmed')">
                            <i class="fas fa-check me-2"></i>Simpan & Konfirmasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="status" id="status" value="pending">
    <input type="hidden" name="total_items" id="totalItemsInput">
    <input type="hidden" name="total_weight" id="totalWeightInput">
    <input type="hidden" name="total_value" id="totalValueInput">
</form>
@endsection

@push('scripts')
<script>
let itemIndex = 1;

document.addEventListener('DOMContentLoaded', function() {
    updateSummary();

    // Add item
    document.getElementById('addItem').addEventListener('click', function() {
        addNewItem();
    });

    // Calculate on change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
            updateItemCalculation(e.target.closest('.item-row'));
            updateSummary();
        }
    });

    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
            removeItem(e.target.closest('.item-row'));
        }
    });
});

function addNewItem() {
    const container = document.getElementById('itemsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'item-row row mb-3';
    newRow.innerHTML = `
        <div class="col-md-4">
            <label class="form-label">Produk <span class="text-danger">*</span></label>
            <select class="form-select product-select" name="items[${itemIndex}][product_id]" required>
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-weight="{{ $product->weight }}">
                    {{ $product->name }} - {{ $product->sku }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Qty <span class="text-danger">*</span></label>
            <input type="number" class="form-control quantity-input" name="items[${itemIndex}][quantity]" min="1" value="1" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Harga Satuan</label>
            <input type="number" class="form-control unit-price" name="items[${itemIndex}][unit_price]" readonly>
        </div>
        <div class="col-md-2">
            <label class="form-label">Berat (kg)</label>
            <input type="number" class="form-control item-weight" name="items[${itemIndex}][weight]" step="0.01" readonly>
        </div>
        <div class="col-md-2">
            <label class="form-label">Aksi</label>
            <button type="button" class="btn btn-danger btn-sm w-100 remove-item">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    container.appendChild(newRow);
    itemIndex++;
    updateRemoveButtons();
}

function removeItem(row) {
    row.remove();
    updateSummary();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const items = document.querySelectorAll('.item-row');
    items.forEach((item, index) => {
        const removeBtn = item.querySelector('.remove-item');
        removeBtn.disabled = items.length === 1;
    });
}

function updateItemCalculation(row) {
    const productSelect = row.querySelector('.product-select');
    const quantityInput = row.querySelector('.quantity-input');
    const unitPriceInput = row.querySelector('.unit-price');
    const weightInput = row.querySelector('.item-weight');

    if (productSelect.value) {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const weight = parseFloat(selectedOption.dataset.weight) || 0;
        const quantity = parseInt(quantityInput.value) || 0;

        unitPriceInput.value = price;
        weightInput.value = (weight * quantity).toFixed(2);
    } else {
        unitPriceInput.value = '';
        weightInput.value = '';
    }
}

function updateSummary() {
    let totalItems = 0;
    let totalWeight = 0;
    let totalValue = 0;

    document.querySelectorAll('.item-row').forEach(row => {
        const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const weight = parseFloat(row.querySelector('.item-weight').value) || 0;

        totalItems += quantity;
        totalWeight += weight;
        totalValue += (quantity * unitPrice);
    });

    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalWeight').textContent = totalWeight.toFixed(2) + ' kg';
    document.getElementById('totalValue').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalValue);

    document.getElementById('totalItemsInput').value = totalItems;
    document.getElementById('totalWeightInput').value = totalWeight;
    document.getElementById('totalValueInput').value = totalValue;
}

function submitWithStatus(status) {
    document.getElementById('status').value = status;
    document.getElementById('shipmentForm').submit();
}
</script>
@endpush
