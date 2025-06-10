@extends('layouts.app')

@section('title', 'Detail Pengiriman - ' . $shipment->shipment_code)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-eye me-2"></i>Detail Pengiriman - {{ $shipment->shipment_code }}</h4>
    <div>
        @if($shipment->status !== 'delivered' && $shipment->status !== 'cancelled')
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
            <i class="fas fa-edit me-2"></i>Update Status
        </button>
        @endif
        <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Informasi Pengiriman -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pengiriman</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Kode Pengiriman:</strong></td>
                                <td>{{ $shipment->shipment_code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Distributor:</strong></td>
                                <td>{{ $shipment->distributor->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat Distributor:</strong></td>
                                <td>{{ $shipment->distributor->address }}, {{ $shipment->distributor->city }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Order:</strong></td>
                                <td>{{ $shipment->order_date->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Prioritas:</strong></td>
                                <td>
                                    @php
                                        $priorityColors = [
                                            'low' => 'secondary',
                                            'medium' => 'info',
                                            'high' => 'warning',
                                            'urgent' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $priorityColors[$shipment->priority] }}">
                                        {{ ucfirst($shipment->priority) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Rute Pengiriman:</strong></td>
                                <td>{{ $shipment->deliverySchedule->route->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jadwal Keberangkatan:</strong></td>
                                <td>{{ $shipment->deliverySchedule->departure_date->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Estimasi Tiba:</strong></td>
                                <td>{{ $shipment->deliverySchedule->estimated_arrival->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Kirim:</strong></td>
                                <td>{{ $shipment->shipped_at ? $shipment->shipped_at->format('d F Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Diterima:</strong></td>
                                <td>{{ $shipment->delivered_at ? $shipment->delivered_at->format('d F Y H:i') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if($shipment->special_instructions)
                <div class="mt-3">
                    <strong>Instruksi Khusus:</strong>
                    <p class="text-muted mt-1">{{ $shipment->special_instructions }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Daftar Produk -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i>Daftar Produk Jamu</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Quantity</th>
                                <th>Harga Satuan</th>
                                <th>Berat (kg)</th>
                                <th>Total Harga</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipment->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->name }}</strong><br>
                                    <small class="text-muted">{{ $item->product->category ?? 'Jamu Tradisional' }}</small>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->weight, 2) }}</td>
                                <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                <td>{{ $item->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tracking History -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-route me-2"></i>Riwayat Tracking</h5>
            </div>
            <div class="card-body">
                @if($shipment->trackings->count() > 0)
                <div class="timeline">
                    @foreach($shipment->trackings as $tracking)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ ucfirst(str_replace('_', ' ', $tracking->status)) }}</h6>
                                    <p class="mb-1">{{ $tracking->description }}</p>
                                    @if($tracking->location)
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $tracking->location }}
                                    </small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">
                                        {{ $tracking->created_at->format('d/m/Y H:i') }}<br>
                                        oleh {{ $tracking->updated_by }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p>Belum ada riwayat tracking</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-flag me-2"></i>Status Pengiriman</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $statusColors = [
                        'pending' => 'secondary',
                        'confirmed' => 'info',
                        'packed' => 'warning',
                        'shipped' => 'primary',
                        'in_transit' => 'warning',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    ];
                @endphp
                <div class="mb-3">
                    <span class="badge bg-{{ $statusColors[$shipment->status] ?? 'secondary' }} p-3 fs-6">
                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                    </span>
                </div>

                <!-- Progress Bar -->
                @php
                    $progress = [
                        'pending' => 10,
                        'confirmed' => 25,
                        'packed' => 40,
                        'shipped' => 60,
                        'in_transit' => 80,
                        'delivered' => 100,
                        'cancelled' => 0
                    ];
                @endphp
                @if($shipment->status !== 'cancelled')
                <div class="progress mb-2" style="height: 10px;">
                    <div class="progress-bar bg-{{ $statusColors[$shipment->status] }}"
                         style="width: {{ $progress[$shipment->status] }}%"></div>
                </div>
                <small class="text-muted">{{ $progress[$shipment->status] }}% Complete</small>
                @endif
            </div>
        </div>

        <!-- Summary Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Ringkasan</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <h4 class="text-primary mb-0">{{ $shipment->total_items }}</h4>
                        <small class="text-muted">Total Item</small>
                    </div>
                    <div class="col-12 mb-3">
                        <h4 class="text-warning mb-0">{{ number_format($shipment->total_weight, 2) }} kg</h4>
                        <small class="text-muted">Total Berat</small>
                    </div>
                    <div class="col-12">
                        <h4 class="text-success mb-0">Rp {{ number_format($shipment->total_value, 0, ',', '.') }}</h4>
                        <small class="text-muted">Total Nilai</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Kontak Distributor</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>{{ $shipment->distributor->name }}</strong></p>
                <p class="mb-1">
                    <i class="fas fa-phone me-2"></i>{{ $shipment->distributor->phone ?? 'N/A' }}
                </p>
                <p class="mb-1">
                    <i class="fas fa-envelope me-2"></i>{{ $shipment->distributor->email ?? 'N/A' }}
                </p>
                <p class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>{{ $shipment->distributor->city }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status - {{ $shipment->shipment_code }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('shipments.update-status', $shipment) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Baru</label>
                        <select class="form-select" id="status" name="status" required>
                            @if($shipment->status === 'pending')
                                <option value="confirmed">Confirmed</option>
                            @elseif($shipment->status === 'confirmed')
                                <option value="packed">Packed</option>
                            @elseif($shipment->status === 'packed')
                                <option value="shipped">Shipped</option>
                            @elseif($shipment->status === 'shipped')
                                <option value="in_transit">In Transit</option>
                            @elseif($shipment->status === 'in_transit')
                                <option value="delivered">Delivered</option>
                            @endif
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Masukkan deskripsi status..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="location" name="location"
                               placeholder="Lokasi saat ini (opsional)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 25px;
}

.timeline-marker {
    position: absolute;
    left: -18px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}
</style>
@endpush
@endsection
