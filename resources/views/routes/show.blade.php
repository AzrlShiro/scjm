@extends('layouts.app')

@section('title', 'Detail Rute: ' . $route->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Rute: {{ $route->name }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('routes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('routes.edit', $route->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $route->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Informasi Rute -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-route"></i> Informasi Rute</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%"><strong>Kode Rute:</strong></td>
                                            <td><span class="badge bg-secondary fs-6">{{ $route->code }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Rute:</strong></td>
                                            <td>{{ $route->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Deskripsi:</strong></td>
                                            <td>{{ $route->description ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jarak:</strong></td>
                                            <td>{{ number_format($route->distance, 2) }} KM</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Estimasi Durasi:</strong></td>
                                            <td>
                                                @php
                                                    $hours = floor($route->estimated_duration / 60);
                                                    $minutes = $route->estimated_duration % 60;
                                                @endphp
                                                @if($hours > 0)
                                                    {{ $hours }} jam {{ $minutes }} menit
                                                @else
                                                    {{ $minutes }} menit
                                                @endif
                                                <small class="text-muted">({{ $route->estimated_duration }} menit)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($route->status == 'active')
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dibuat:</strong></td>
                                            <td>{{ $route->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Diperbarui:</strong></td>
                                            <td>{{ $route->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Waypoints -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Waypoints</h5>
                                </div>
                                <div class="card-body">
                                    @if($route->waypoints && count($route->waypoints) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Latitude</th>
                                                        <th>Longitude</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($route->waypoints as $index => $waypoint)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $waypoint['lat'] ?? '-' }}</td>
                                                            <td>{{ $waypoint['lng'] ?? '-' }}</td>
                                                            <td>
                                                                @if(isset($waypoint['lat']) && isset($waypoint['lng']))
                                                                    <a href="https://www.google.com/maps?q={{ $waypoint['lat'] }},{{ $waypoint['lng'] }}"
                                                                       target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat di Maps">
                                                                        <i class="fas fa-external-link-alt"></i>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="fas fa-map-marker-alt fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">Tidak ada waypoints yang didefinisikan</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Pengiriman -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Jadwal Pengiriman</h5>
                                    <span class="badge bg-info">{{ $route->deliverySchedules->count() }} Jadwal</span>
                                </div>
                                <div class="card-body">
                                    @if($route->deliverySchedules->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Tanggal</th>
                                                        <th>Waktu Mulai</th>
                                                        <th>Waktu Selesai</th>
                                                        <th>Driver</th>
                                                        <th>Kendaraan</th>
                                                        <th>Jumlah Pengiriman</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($route->deliverySchedules as $index => $schedule)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}</td>
                                                            <td>{{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-' }}</td>
                                                            <td>{{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '-' }}</td>
                                                            <td>{{ $schedule->driver_name ?? '-' }}</td>
                                                            <td>{{ $schedule->vehicle_info ?? '-' }}</td>
                                                            <td>{{ $schedule->shipments->count() }} pengiriman</td>
                                                            <td>
                                                                @switch($schedule->status)
                                                                    @case('scheduled')
                                                                        <span class="badge bg-warning">Terjadwal</span>
                                                                        @break
                                                                    @case('in_progress')
                                                                        <span class="badge bg-primary">Berlangsung</span>
                                                                        @break
                                                                    @case('completed')
                                                                        <span class="badge bg-success">Selesai</span>
                                                                        @break
                                                                    @case('cancelled')
                                                                        <span class="badge bg-danger">Dibatalkan</span>
                                                                        @break
                                                                    @default
                                                                        <span class="badge bg-secondary">{{ ucfirst($schedule->status) }}</span>
                                                                @endswitch
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <h6>Belum ada jadwal pengiriman</h6>
                                            <p class="text-muted">Rute ini belum memiliki jadwal pengiriman.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus rute <strong>{{ $route->name }}</strong>?</p>
                <p class="text-danger"><small>Data yang sudah dihapus tidak dapat dikembalikan.</small></p>
                @if($route->deliverySchedules->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Peringatan:</strong> Rute ini memiliki {{ $route->deliverySchedules->count() }} jadwal pengiriman yang akan ikut terhapus.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(routeId) {
    const form = document.getElementById('deleteForm');
    form.action = `/routes/${routeId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
