@extends('layouts.app')

@section('title', 'Tambah Jadwal Produksi Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Tambah Jadwal Produksi Baru</h4>
                    <a href="{{ route('production.schedules.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('production.schedules.store') }}" method="POST" id="productionScheduleForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jamu_product_id" class="form-label">Produk Jamu <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jamu_product_id') is-invalid @enderror" id="jamu_product_id" name="jamu_product_id">
                                        <option value="">Pilih Produk Jamu...</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('jamu_product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} ({{ $product->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jamu_product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="recipe_id" class="form-label">Resep <span class="text-danger">*</span></label>
                                    <select class="form-select @error('recipe_id') is-invalid @enderror" id="recipe_id" name="recipe_id">
                                        <option value="">Pilih Resep...</option>
                                        {{-- Resep akan diisi dinamis berdasarkan produk jamu yang dipilih --}}
                                        @foreach($recipes as $recipe)
                                            <option
                                                value="{{ $recipe->id }}"
                                                data-jamu-product-id="{{ $recipe->jamu_product_id }}"
                                                {{ old('recipe_id') == $recipe->id ? 'selected' : '' }}
                                                style="display: {{ (old('jamu_product_id') && old('jamu_product_id') == $recipe->jamu_product_id) ? 'block' : 'none' }};"
                                            >
                                                {{ $recipe->name }} ({{ $recipe->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('recipe_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="scheduled_date" class="form-label">Tanggal Dijadwalkan <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('scheduled_date') is-invalid @enderror"
                                           id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}">
                                    @error('scheduled_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="planned_quantity" class="form-label">Kuantitas Direncanakan (Unit) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('planned_quantity') is-invalid @enderror"
                                           id="planned_quantity" name="planned_quantity" value="{{ old('planned_quantity') }}"
                                           min="1" placeholder="Misal: 500">
                                    @error('planned_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Catatan tambahan untuk jadwal produksi ini (optional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('production.schedules.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batalkan
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Jadwal Produksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jamuProductIdField = document.getElementById('jamu_product_id');
    const recipeIdField = document.getElementById('recipe_id');
    const allRecipeOptions = recipeIdField.querySelectorAll('option');

    function filterRecipesByJamuProduct() {
        const selectedJamuProductId = jamuProductIdField.value;

        // Reset dan sembunyikan semua opsi resep kecuali yang "Pilih Resep..."
        allRecipeOptions.forEach(option => {
            if (option.value === "") {
                option.style.display = 'block'; // Selalu tampilkan opsi default
            } else {
                option.style.display = 'none';
            }
        });

        // Tampilkan opsi resep yang sesuai dengan produk jamu yang dipilih
        if (selectedJamuProductId) {
            let firstRecipeFound = false;
            allRecipeOptions.forEach(option => {
                if (option.dataset.jamuProductId === selectedJamuProductId) {
                    option.style.display = 'block';
                    if (!firstRecipeFound) {
                        // Secara otomatis pilih resep pertama yang cocok jika belum ada yang terpilih
                        if (recipeIdField.value !== option.value) { // Hindari mengubah jika sudah terpilih
                            recipeIdField.value = option.value;
                        }
                        firstRecipeFound = true;
                    }
                }
            });
            if (!firstRecipeFound) {
                 recipeIdField.value = ""; // Reset jika tidak ada resep yang cocok
            }
        } else {
            recipeIdField.value = ""; // Reset jika tidak ada produk jamu yang dipilih
        }
    }

    // Panggil saat halaman dimuat (untuk old input)
    filterRecipesByJamuProduct();

    // Panggil saat produk jamu berubah
    jamuProductIdField.addEventListener('change', filterRecipesByJamuProduct);

    // Form validation (tetap dari Blade sebelumnya, disesuaikan)
    document.getElementById('productionScheduleForm').addEventListener('submit', function(e) {
        const jamuProductId = document.getElementById('jamu_product_id').value;
        const recipeId = document.getElementById('recipe_id').value;
        const scheduledDate = document.getElementById('scheduled_date').value;
        const plannedQuantity = document.getElementById('planned_quantity').value;

        if (!jamuProductId) {
            e.preventDefault();
            alert('Produk Jamu harus dipilih!');
            document.getElementById('jamu_product_id').focus();
            return;
        }

        if (!recipeId) {
            e.preventDefault();
            alert('Resep harus dipilih!');
            document.getElementById('recipe_id').focus();
            return;
        }

        if (!scheduledDate) {
            e.preventDefault();
            alert('Tanggal Dijadwalkan harus diisi!');
            document.getElementById('scheduled_date').focus();
            return;
        }

        if (!plannedQuantity || parseInt(plannedQuantity) < 1) { // Min 1 sesuai controller
            e.preventDefault();
            alert('Kuantitas yang direncanakan harus diisi dengan angka minimal 1!');
            document.getElementById('planned_quantity').focus();
            return;
        }
    });
});
</script>
@endsection
