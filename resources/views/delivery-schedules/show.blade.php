@extends('layouts.app')

@section('title', 'Detail Jadwal Pengiriman: ' . $deliverySchedule->schedule_code)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Jadwal Pengiriman: {{ $deliverySchedule->schedule_code }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('delivery-schedules.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                        <a href="{{ route('delivery-schedules.edit', $deliverySchedule->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Jadwal
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $deliverySchedule->id }})">
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
                        <!-- Informasi Dasar -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Dasar</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Kode Jadwal:</strong></td>
                                            <td><span class="badge bg-primary">{{ $deliverySchedule->schedule_code }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @switch($deliverySchedule->status)
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
                                                        <span class="badge bg-secondary">{{ ucfirst($deliverySchedule->status) }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Pengiriman:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($deliverySchedule->delivery_date)->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Waktu Keberangkatan:</strong></td>
                                            <td><strong>{{ \Carbon\Carbon::parse($deliverySchedule->departure_time)->format('H:i') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Estimasi Waktu Tiba:</strong></td>
                                            <td><strong>{{ \Carbon\Carbon::parse($deliverySchedule->estimated_arrival_time)->format('H:i') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Durasi Perjalanan:</strong></td>
                                            <td>
                                                @php
                                                    $departure = \Carbon\Carbon::parse($deliverySchedule->departure_time);
                                                    $arrival = \Carbon\Carbon::parse($deliverySchedule->estimated_arrival_time);
                                                    $duration = $departure->diff($arrival);
                                                @endphp
                                                {{ $duration->h }} jam {{ $duration->i }} menit
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Rute -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-route"></i> Informasi Rute</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Nama Rute:</strong></td>
                                            <td><strong>{{ $deliverySchedule->route->name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kode Rute:</strong></td>
                                            <td><span class="badge bg-success">{{ $deliverySchedule->route->code }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Titik Awal:</strong></td>
                                            <td>{{ $deliverySchedule->route->start_point ?? 'Tidak tersedia' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Titik Akhir:</strong></td>
                                            <td>{{ $deliverySchedule->route->end_point ?? 'Tidak tersedia' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jarak:</strong></td>
                                            <td>{{ $deliverySchedule->route->distance ?? 'Tidak tersedia' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Deskripsi Rute:</strong></td>
                                            <td>{{ $deliverySchedule->route->description ?? 'Tidak ada deskripsi' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Informasi Kendaraan & Driver -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="fas fa-truck"></i> Kendaraan & Driver</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Tipe Kendaraan:</strong></td>
                                            <td><span class="badge bg-secondary">{{ $deliverySchedule->vehicle_type }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nomor Kendaraan:</strong></td>
                                            <td><strong>{{ $deliverySchedule->vehicle_number }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kapasitas Berat:</strong></td>
                                            <td><strong>{{ number_format($deliverySchedule->capacity_weight, 0) }} kg</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Driver:</strong></td>
                                            <td><strong>{{ $deliverySchedule->driver_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Telepon Driver:</strong></td>
                                            <td>
                                                <a href="tel:{{ $deliverySchedule->driver_phone }}" class="text-decoration-none">
                                                    <i class="fas fa-phone"></i> {{ $deliverySchedule->driver_phone }}
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan & Informasi Tambahan -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Catatan & Info Tambahan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Catatan:</strong>
                                        <div class="mt-2">
                                            @if($deliverySchedule->notes)
                                                <div class="alert alert-light">
                                                    {{ $deliverySchedule->notes }}
                                                </div>
                                            @else
                                                <em class="text-muted">Tidak ada catatan</em>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td><strong>Dibuat pada:</strong></td>
                                            <td>{{ $deliverySchedule->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Terakhir diperbarui:</strong></td>
                                            <td>{{ $deliverySchedule->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jumlah Pengiriman:</strong></td>
                                            <td><span class="badge bg-info">{{ $deliverySchedule->shipments->count() }}</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Pengiriman -->
                    @if($deliverySchedule->shipments->count() > 0)
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> Daftar Pengiriman ({{ $deliverySchedule->shipments->count() }})</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Kode Pengiriman</th>
                                                <th>Pengirim</th>
                                                <th>Penerima</th>
                                                <th>Alamat Tujuan</th>
                                                <th>Berat</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($deliverySchedule->shipments as $index => $shipment)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><span class="badge bg-primary">{{ $shipment->shipment_code }}</span></td>
                                                    <td>
                                                        <strong>{{ $shipment->sender_name }}</strong>
                                                        <br><small class="text-muted">{{ $shipment->sender_phone }}</small>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $shipment->recipient_name }}</strong>
                                                        <br><small class="text-muted">{{ $shipment->recipient_phone }}</small>
                                                    </td>
                                                    <td>{{ Str::limit($shipment->recipient_address, 50) }}</td>
                                                    <td>{{ number_format($shipment->weight, 2) }} kg</td>
                                                    <td>
                                                        @switch($shipment->status)
                                                            @case('pending')
                                                                <span class="badge bg-warning">Menunggu</span>
                                                                @break
                                                            @case('in_transit')
                                                                <span class="badge bg-primary">Dalam Perjalanan</span>
                                                                @break
                                                            @case('delivered')
                                                                <span class="badge bg-success">Terkirim</span>
                                                                @break
                                                            @case('cancelled')
                                                                <span class="badge bg-danger">Dibatalkan</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ ucfirst($shipment->status) }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('shipments.show', $shipment->id) }}"
                                                           class="btn btn-sm btn-outline-info" title="Detail Pengiriman">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> Daftar Pengiriman</h5>
                            </div>
                            <div class="card-body text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <h5>Belum ada pengiriman</h5>
                                <p class="text-muted">Jadwal ini belum memiliki pengiriman yang terkait.</p>
                                <a href="{{ route('shipments.create') }}?schedule_id={{ $deliverySchedule->id }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Pengiriman
                                </a>
                            </div>
                        </div>
                    @endif
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
                <p>Apakah Anda yakin ingin menghapus jadwal pengiriman ini?</p>
                @if($deliverySchedule->shipments->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian!</strong> Jadwal ini memiliki {{ $deliverySchedule->shipments->count() }} pengiriman terkait yang juga akan terhapus.
                    </div>
                @endif
                <p class="text-danger"><small>Data yang sudah dihapus tidak dapat dikembalikan.</small></p>
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
function confirmDelete(scheduleId) {
    const form = document.getElementById('deleteForm');
    form.action = `/delivery-schedules/${scheduleId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Auto refresh page every 30 seconds for real-time updates
setInterval(function() {
    if (document.visibilityState === 'visible') {
        // Only refresh if page is visible to avoid unnecessary requests
        location.reload();
    }
}, 30000);
</script>
@endsection
