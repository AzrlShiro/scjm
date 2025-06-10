@extends('layouts.app')

@section('title', 'Tambah Jadwal Pengiriman Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Tambah Jadwal Pengiriman Baru</h4>
                    <a href="{{ route('delivery-schedules.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('delivery-schedules.store') }}" method="POST" id="scheduleForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="schedule_code" class="form-label">Kode Jadwal <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('schedule_code') is-invalid @enderror"
                                           id="schedule_code" name="schedule_code" value="{{ old('schedule_code') }}"
                                           placeholder="Contoh: SCH001">
                                    @error('schedule_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="route_id" class="form-label">Rute <span class="text-danger">*</span></label>
                                    <select class="form-select @error('route_id') is-invalid @enderror" id="route_id" name="route_id">
                                        <option value="">Pilih Rute...</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>
                                                {{ $route->name }} ({{ $route->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('route_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="delivery_date" class="form-label">Tanggal Pengiriman <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('delivery_date') is-invalid @enderror"
                                           id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}"
                                           min="{{ date('Y-m-d') }}">
                                    @error('delivery_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="departure_time" class="form-label">Waktu Keberangkatan <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('departure_time') is-invalid @enderror"
                                           id="departure_time" name="departure_time" value="{{ old('departure_time') }}">
                                    @error('departure_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="estimated_arrival_time" class="form-label">Estimasi Waktu Tiba <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('estimated_arrival_time') is-invalid @enderror"
                                           id="estimated_arrival_time" name="estimated_arrival_time" value="{{ old('estimated_arrival_time') }}">
                                    @error('estimated_arrival_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_type" class="form-label">Tipe Kendaraan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type">
                                        <option value="">Pilih Tipe Kendaraan...</option>
                                        <option value="Truck" {{ old('vehicle_type') == 'Truck' ? 'selected' : '' }}>Truck</option>
                                        <option value="Van" {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}>Van</option>
                                        <option value="Pick Up" {{ old('vehicle_type') == 'Pick Up' ? 'selected' : '' }}>Pick Up</option>
                                        <option value="Motor" {{ old('vehicle_type') == 'Motor' ? 'selected' : '' }}>Motor</option>
                                    </select>
                                    @error('vehicle_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_number" class="form-label">Nomor Kendaraan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror"
                                           id="vehicle_number" name="vehicle_number" value="{{ old('vehicle_number') }}"
                                           placeholder="Contoh: B 1234 ABC">
                                    @error('vehicle_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="driver_name" class="form-label">Nama Driver <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('driver_name') is-invalid @enderror"
                                           id="driver_name" name="driver_name" value="{{ old('driver_name') }}"
                                           placeholder="Masukkan nama driver">
                                    @error('driver_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="driver_phone" class="form-label">Nomor Telepon Driver <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('driver_phone') is-invalid @enderror"
                                           id="driver_phone" name="driver_phone" value="{{ old('driver_phone') }}"
                                           placeholder="Contoh: 081234567890">
                                    @error('driver_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="capacity_weight" class="form-label">Kapasitas Berat (KG) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('capacity_weight') is-invalid @enderror"
                                               id="capacity_weight" name="capacity_weight" value="{{ old('capacity_weight') }}"
                                               step="0.01" min="0" placeholder="1000">
                                        <span class="input-group-text">KG</span>
                                        @error('capacity_weight')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="scheduled" {{ old('status', 'scheduled') == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>Berlangsung</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Catatan tambahan untuk jadwal pengiriman ini (optional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('delivery-schedules.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batalkan
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Jadwal
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
    // Auto-generate schedule code
    function generateScheduleCode() {
        const date = new Date();
        const year = date.getFullYear().toString().substr(-2);
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const random = String(Math.floor(Math.random() * 100)).padStart(2, '0');
        return `SCH${year}${month}${day}${random}`;
    }

    // Generate code if field is empty
    const scheduleCodeField = document.getElementById('schedule_code');
    if (!scheduleCodeField.value) {
        scheduleCodeField.value = generateScheduleCode();
    }

    // Auto-calculate estimated arrival time when departure time and route change
    const departureTimeField = document.getElementById('departure_time');
    const routeField = document.getElementById('route_id');
    const estimatedArrivalField = document.getElementById('estimated_arrival_time');

    function calculateEstimatedArrival() {
        const departureTime = departureTimeField.value;
        const routeId = routeField.value;

        if (departureTime && routeId) {
            // Get route duration (you might want to fetch this via AJAX)
            // For now, we'll add a default 2 hours
            const departure = new Date(`2000-01-01T${departureTime}:00`);
            departure.setHours(departure.getHours() + 2); // Add 2 hours as default

            const hours = String(departure.getHours()).padStart(2, '0');
            const minutes = String(departure.getMinutes()).padStart(2, '0');

            if (!estimatedArrivalField.value) {
                estimatedArrivalField.value = `${hours}:${minutes}`;
            }
        }
    }

    departureTimeField.addEventListener('change', calculateEstimatedArrival);
    routeField.addEventListener('change', calculateEstimatedArrival);

    // Form validation
    document.getElementById('scheduleForm').addEventListener('submit', function(e) {
        const scheduleCode = document.getElementById('schedule_code').value.trim();
        const routeId = document.getElementById('route_id').value;
        const deliveryDate = document.getElementById('delivery_date').value;
        const departureTime = document.getElementById('departure_time').value;
        const estimatedArrivalTime = document.getElementById('estimated_arrival_time').value;
        const vehicleType = document.getElementById('vehicle_type').value;
        const vehicleNumber = document.getElementById('vehicle_number').value.trim();
        const driverName = document.getElementById('driver_name').value.trim();
        const driverPhone = document.getElementById('driver_phone').value.trim();
        const capacityWeight = document.getElementById('capacity_weight').value;

        if (!scheduleCode) {
            e.preventDefault();
            alert('Kode jadwal harus diisi!');
            document.getElementById('schedule_code').focus();
            return;
        }

        if (!routeId) {
            e.preventDefault();
            alert('Rute harus dipilih!');
            document.getElementById('route_id').focus();
            return;
        }

        if (!deliveryDate) {
            e.preventDefault();
            alert('Tanggal pengiriman harus diisi!');
            document.getElementById('delivery_date').focus();
            return;
        }

        if (!departureTime) {
            e.preventDefault();
            alert('Waktu keberangkatan harus diisi!');
            document.getElementById('departure_time').focus();
            return;
        }

        if (!estimatedArrivalTime) {
            e.preventDefault();
            alert('Estimasi waktu tiba harus diisi!');
            document.getElementById('estimated_arrival_time').focus();
            return;
        }

        if (!vehicleType) {
            e.preventDefault();
            alert('Tipe kendaraan harus dipilih!');
            document.getElementById('vehicle_type').focus();
            return;
        }

        if (!vehicleNumber) {
            e.preventDefault();
            alert('Nomor kendaraan harus diisi!');
            document.getElementById('vehicle_number').focus();
            return;
        }

        if (!driverName) {
            e.preventDefault();
            alert('Nama driver harus diisi!');
            document.getElementById('driver_name').focus();
            return;
        }

        if (!driverPhone) {
            e.preventDefault();
            alert('Nomor telepon driver harus diisi!');
            document.getElementById('driver_phone').focus();
            return;
        }

        if (!capacityWeight || parseFloat(capacityWeight) <= 0) {
            e.preventDefault();
            alert('Kapasitas berat harus diisi dengan nilai yang valid!');
            document.getElementById('capacity_weight').focus();
            return;
        }

        // Validate time logic
        if (departureTime && estimatedArrivalTime) {
            const departure = new Date(`2000-01-01T${departureTime}:00`);
            const arrival = new Date(`2000-01-01T${estimatedArrivalTime}:00`);

            if (arrival <= departure) {
                e.preventDefault();
                alert('Estimasi waktu tiba harus lebih besar dari waktu keberangkatan!');
                document.getElementById('estimated_arrival_time').focus();
                return;
            }
        }
    });
});
</script>
@endsection
