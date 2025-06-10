@extends('layouts.app')

@section('title', 'Edit Jadwal Produksi: #' . $schedule->id)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Jadwal Produksi: #{{ $schedule->id }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('production.schedules.show', $schedule->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('production.schedules.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Daftar Jadwal
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('production.schedules.update', $schedule->id) }}" method="POST" id="productionScheduleForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jamu_product_id" class="form-label">Produk Jamu <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jamu_product_id') is-invalid @enderror" id="jamu_product_id" name="jamu_product_id">
                                        <option value="">Pilih Produk Jamu...</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('jamu_product_id', $schedule->jamu_product_id) == $product->id ? 'selected' : '' }}>
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
                                        @foreach($recipes as $recipe)
                                            <option
                                                value="{{ $recipe->id }}"
                                                data-jamu-product-id="{{ $recipe->jamu_product_id }}"
                                                {{ old('recipe_id', $schedule->recipe_id) == $recipe->id ? 'selected' : '' }}
                                                style="display: {{ (old('jamu_product_id', $schedule->jamu_product_id) == $recipe->jamu_product_id) ? 'block' : 'none' }};"
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
                                           id="scheduled_date" name="scheduled_date"
                                           value="{{ old('scheduled_date', $schedule->scheduled_date->format('Y-m-d')) }}">
                                    @error('scheduled_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="planned_quantity" class="form-label">Kuantitas Direncanakan (Unit) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('planned_quantity') is-invalid @enderror"
                                           id="planned_quantity" name="planned_quantity"
                                           value="{{ old('planned_quantity', $schedule->planned_quantity) }}"
                                           min="1" placeholder="Misal: 500">
                                    @error('planned_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Planned Batches tidak perlu diinput karena dihitung otomatis --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="scheduled" {{ old('status', $schedule->status) == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                <option value="in_progress" {{ old('status', $schedule->status) == 'in_progress' ? 'selected' : '' }}>Berlangsung</option>
                                <option value="completed" {{ old('status', $schedule->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Catatan tambahan untuk jadwal produksi ini (optional)">{{ old('notes', $schedule->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Informasi Pembaruan:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Jadwal ini dibuat pada: {{ $schedule->created_at->format('d/m/Y H:i') }}</li>
                                <li>Terakhir diperbarui: {{ $schedule->updated_at->format('d/m/Y H:i') }}</li>
                                @if($schedule->productionBatches->count() > 0)
                                    <li class="text-warning">Jadwal ini memiliki {{ $schedule->productionBatches->count() }} batch produksi terkait</li>
                                @endif
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('production.schedules.show', $schedule->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batalkan
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Perbarui Jadwal Produksi
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
        const currentRecipeId = "{{ old('recipe_id', $schedule->recipe_id) }}"; // Ambil nilai resep yang sedang terpilih

        // Reset dan sembunyikan semua opsi resep kecuali yang "Pilih Resep..."
        allRecipeOptions.forEach(option => {
            if (option.value === "") {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });

        // Tampilkan opsi resep yang sesuai dengan produk jamu yang dipilih
        let firstRecipeFound = false;
        let selectedRecipeStillValid = false;

        if (selectedJamuProductId) {
            allRecipeOptions.forEach(option => {
                if (option.dataset.jamuProductId === selectedJamuProductId) {
                    option.style.display = 'block';
                    if (!firstRecipeFound) {
                        // Coba pilih resep yang sebelumnya terpilih jika masih valid
                        if (option.value === currentRecipeId) {
                            recipeIdField.value = currentRecipeId;
                            selectedRecipeStillValid = true;
                        }
                        firstRecipeFound = true;
                    }
                }
            });

            // Jika resep yang sebelumnya terpilih tidak valid untuk produk baru, pilih yang pertama
            if (firstRecipeFound && !selectedRecipeStillValid) {
                const firstValidRecipe = recipeIdField.querySelector(`option[data-jamu-product-id="${selectedJamuProductId}"]`);
                if (firstValidRecipe) {
                    recipeIdField.value = firstValidRecipe.value;
                } else {
                    recipeIdField.value = ""; // Tidak ada resep valid, reset
                }
            } else if (!firstRecipeFound) {
                recipeIdField.value = ""; // Tidak ada resep yang cocok sama sekali
            }

        } else {
            recipeIdField.value = ""; // Jika tidak ada produk jamu yang dipilih, reset resep
        }
    }

    // Panggil saat halaman dimuat (untuk old input atau nilai dari $schedule)
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

        // Confirm update
        if (!confirm('Apakah Anda yakin ingin memperbarui jadwal produksi ini?')) {
            e.preventDefault();
            return;
        }
    });
});
</script>
@endsection
