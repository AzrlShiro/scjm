@extends('layouts.app')

@section('title', 'Edit Rute: ' . $route->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Rute: {{ $route->name }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('routes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('routes.show', $route->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Detail
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

                    <form action="{{ route('routes.update', $route->id) }}" method="POST" id="routeForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Rute <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $route->name) }}"
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
                                           id="code" name="code" value="{{ old('code', $route->code) }}"
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
                                      placeholder="Deskripsi optional untuk rute ini">{{ old('description', $route->description) }}</textarea>
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
                                               id="distance" name="distance" value="{{ old('distance', $route->distance) }}"
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
                                               id="estimated_duration" name="estimated_duration" value="{{ old('estimated_duration', $route->estimated_duration) }}"
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
                                <option value="active" {{ old('status', $route->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $route->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="waypoints" class="form-label">Waypoints (Koordinat)</label>
                            <div id="waypoints-container">
                                @if($route->waypoints && count($route->waypoints) > 0)
                                    @foreach($route->waypoints as $index => $waypoint)
                                        <div class="waypoint-item mb-2">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <input type="number" class="form-control" name="waypoints[{{ $index }}][lat]"
                                                           placeholder="Latitude" step="any" value="{{ $waypoint['lat'] ?? '' }}">
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="number" class="form-control" name="waypoints[{{ $index }}][lng]"
                                                           placeholder="Longitude" step="any" value="{{ $waypoint['lng'] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-waypoint"
                                                            {{ count($route->waypoints) == 1 ? 'disabled' : '' }}>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
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
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-waypoint">
                                <i class="fas fa-plus"></i> Tambah Waypoint
                            </button>
                            <small class="form-text text-muted d-block mt-1">
                                Tambahkan koordinat titik-titik penting dalam rute (optional)
                            </small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('routes.show', $route->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batalkan
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Rute
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
    // Set initial waypoint index based on existing waypoints
    let waypointIndex = document.querySelectorAll('.waypoint-item').length;

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

    // Initialize remove buttons state
    updateRemoveButtons();

    // Auto-generate code based on name (only if code is empty or matches pattern)
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const codeField = document.getElementById('code');
        const currentCode = codeField.value;

        // Only auto-generate if code is empty or follows the RT pattern
        if (name && (!currentCode || currentCode.match(/^RT[A-Z]{0,3}\d{3}$/))) {
            const code = 'RT' + name.substring(0, 3).toUpperCase().replace(/[^A-Z]/g, '') +
                        String(Math.floor(Math.random() * 1000)).padStart(3, '0');
            codeField.value = code;
        }
    });

    // Form validation
    document.getElementById('routeForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const code = document.getElementById('code').value.trim();
        const distance = document.getElementById('distance').value;
        const duration = document.getElementById('estimated_duration').value;

        if (!name) {
            e.preventDefault();
            alert('Nama rute harus diisi!');
            document.getElementById('name').focus();
            return;
        }

        if (!code) {
            e.preventDefault();
            alert('Kode rute harus diisi!');
            document.getElementById('code').focus();
            return;
        }

        if (!distance || parseFloat(distance) <= 0) {
            e.preventDefault();
            alert('Jarak harus diisi dengan nilai yang valid!');
            document.getElementById('distance').focus();
            return;
        }

        if (!duration || parseInt(duration) <= 0) {
            e.preventDefault();
            alert('Estimasi durasi harus diisi dengan nilai yang valid!');
            document.getElementById('estimated_duration').focus();
            return;
        }
    });
});
</script>
@endsection
