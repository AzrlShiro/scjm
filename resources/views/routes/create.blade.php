@extends('layouts.app')

@section('title', 'Tambah Rute Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Tambah Rute Baru</h4>
                    <a href="{{ route('routes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('routes.store') }}" method="POST" id="routeForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Rute <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Masukkan nama rute">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Kode Rute <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                           id="code" name="code" value="{{ old('code') }}"
                                           placeholder="Contoh: RT001">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Deskripsi optional untuk rute ini">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="distance" class="form-label">Jarak (KM) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('distance') is-invalid @enderror"
                                               id="distance" name="distance" value="{{ old('distance') }}"
                                               step="0.01" min="0" placeholder="0.00">
                                        <span class="input-group-text">KM</span>
                                        @error('distance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estimated_duration" class="form-label">Estimasi Durasi (Menit) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('estimated_duration') is-invalid @enderror"
                                               id="estimated_duration" name="estimated_duration" value="{{ old('estimated_duration') }}"
                                               min="0" placeholder="60">
                                        <span class="input-group-text">Menit</span>
                                        @error('estimated_duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Contoh: 90 menit = 1 jam 30 menit
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="waypoints" class="form-label">Waypoints (Koordinat)</label>
                            <div id="waypoints-container">
                                <div class="waypoint-item mb-2">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="number" class="form-control" name="waypoints[0][lat]"
                                                   placeholder="Latitude" step="any">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="number" class="form-control" name="waypoints[0][lng]"
                                                   placeholder="Longitude" step="any">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-waypoint" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-waypoint">
                                <i class="fas fa-plus"></i> Tambah Waypoint
                            </button>
                            <small class="form-text text-muted d-block mt-1">
                                Tambahkan koordinat titik-titik penting dalam rute (optional)
                            </small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('routes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batalkan
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Rute
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
    let waypointIndex = 1;

    // Add waypoint functionality
    document.getElementById('add-waypoint').addEventListener('click', function() {
        const container = document.getElementById('waypoints-container');
        const newWaypoint = document.createElement('div');
        newWaypoint.className = 'waypoint-item mb-2';
        newWaypoint.innerHTML = `
            <div class="row">
                <div class="col-md-5">
                    <input type="number" class="form-control" name="waypoints[${waypointIndex}][lat]"
                           placeholder="Latitude" step="any">
                </div>
                <div class="col-md-5">
                    <input type="number" class="form-control" name="waypoints[${waypointIndex}][lng]"
                           placeholder="Longitude" step="any">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-waypoint">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newWaypoint);
        waypointIndex++;
        updateRemoveButtons();
    });

    // Remove waypoint functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-waypoint')) {
            e.target.closest('.waypoint-item').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const waypoints = document.querySelectorAll('.waypoint-item');
        waypoints.forEach((waypoint, index) => {
            const removeBtn = waypoint.querySelector('.remove-waypoint');
            removeBtn.disabled = waypoints.length === 1;
        });
    }

    // Auto-generate code based on name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const codeField = document.getElementById('code');
        if (name && !codeField.value) {
            const code = 'RT' + name.substring(0, 3).toUpperCase().replace(/[^A-Z]/g, '') +
                        String(Math.floor(Math.random() * 1000)).padStart(3, '0');
            codeField.value = code;
        }
    });
});
</script>
@endsection
