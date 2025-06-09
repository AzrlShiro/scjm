@extends('layouts.app')

@section('title', 'Detail Pengiriman')

@section('content')
<div class="row">
    <!-- Detail Umum -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Detail Pengiriman: {{ $shipment->shipment_code }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Distributor:</strong></td>
                                <td>{{ $shipment->distributor->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat:</strong></td>
                                <td>{{ $shipment->distributor->full_address }}</td>
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
                                            'low' => 'success',
                                            'medium' => 'warning',
                                            'high' => 'danger',
                                            'urgent' => 'dark'
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
                                <td><strong>Total Berat:</strong></td>
                                <td>{{ number_format($shipment->total_weight, 2) }} kg</td>
                            </tr>
                            <tr>
                                <td><strong>Total Item:</strong></td>
                                <td>{{ $shipment->total_items }} item</td>
                            </tr>
                            <tr>
                                <td><strong>Total Nilai:</strong></td>
                                <td>Rp {{ number_format($shipment->total_value, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
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
                                    <span class="badge bg-{{ $statusColors[$shipment->status] ?? 'secondary' }} fs-6">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($shipment->special_instructions)
                <div class="alert alert-info">
                    <strong>Instruksi Khusus:</strong> {{ $shipment->special_instructions }}
                </div>
                @endif
            </div>
        </div>

        <!-- Item Pengiriman -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-boxes me-2"></i>Item Pengiriman</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Berat</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shipment->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}"
                                             class="me-3"
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        @endif
                                        <div>
                                            <strong>{{ $item->product->name }}</strong>
                                            @if($item->product->sku)
                                            <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->weight, 2) }} kg</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada item pengiriman</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th colspan="4">Total</th>
                                <th>Rp {{ number_format($shipment->total_value, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tracking History -->
        @if($shipment->trackingHistory && $shipment->trackingHistory->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-route me-2"></i>Riwayat Tracking</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($shipment->trackingHistory->sortByDesc('created_at') as $track)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-{{ $statusColors[$track->status] ?? 'secondary' }}"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">{{ ucfirst(str_replace('_', ' ', $track->status)) }}</h6>
                            <p class="mb-1">{{ $track->description }}</p>
                            <small class="text-muted">
                                {{ $track->created_at->format('d F Y H:i') }}
                                @if($track->location)
                                • {{ $track->location }}
                                @endif
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-cog me-2"></i>Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($shipment->status === 'pending')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal">
                        <i class="fas fa-check me-2"></i>Konfirmasi
                    </button>
                    @endif

                    @if(in_array($shipment->status, ['confirmed', 'packed']))
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#shipModal">
                        <i class="fas fa-shipping-fast me-2"></i>Kirim
                    </button>
                    @endif

                    @if($shipment->status !== 'cancelled')
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                        <i class="fas fa-edit me-2"></i>Update Status
                    </button>
                    @endif

                    <a href="{{ route('shipments.print', $shipment) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-print me-2"></i>Cetak Label
                    </a>

                    <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>

                    @if($shipment->status === 'pending')
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i class="fas fa-times me-2"></i>Batalkan
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informasi Pengiriman -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-truck me-2"></i>Info Pengiriman</h5>
            </div>
            <div class="card-body">
                @if($shipment->courier)
                <p><strong>Kurir:</strong> {{ $shipment->courier->name }}</p>
                <p><strong>Service:</strong> {{ $shipment->service_type }}</p>
                @endif

                @if($shipment->tracking_number)
                <p><strong>No. Resi:</strong>
                    <span class="badge bg-info">{{ $shipment->tracking_number }}</span>
                </p>
                @endif

                @if($shipment->estimated_delivery)
                <p><strong>Estimasi Tiba:</strong> {{ $shipment->estimated_delivery->format('d F Y') }}</p>
                @endif

                @if($shipment->delivery_cost)
                <p><strong>Biaya Kirim:</strong> Rp {{ number_format($shipment->delivery_cost, 0, ',', '.') }}</p>
                @endif
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-address-book me-2"></i>Kontak</h5>
            </div>
            <div class="card-body">
                @if($shipment->distributor->phone)
                <p><strong>Telepon:</strong>
                    <a href="tel:{{ $shipment->distributor->phone }}">{{ $shipment->distributor->phone }}</a>
                </p>
                @endif

                @if($shipment->distributor->email)
                <p><strong>Email:</strong>
                    <a href="mailto:{{ $shipment->distributor->email }}">{{ $shipment->distributor->email }}</a>
                </p>
                @endif

                @if($shipment->contact_person)
                <p><strong>PIC:</strong> {{ $shipment->contact_person }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('shipments.modals.confirm')
@include('shipments.modals.ship')
@include('shipments.modals.update-status')
@include('shipments.modals.cancel')

@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    width: 2px;
    height: calc(100% + 10px);
    background-color: #dee2e6;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto refresh tracking status every 30 seconds
    @if(in_array($shipment->status, ['shipped', 'in_transit']))
    setInterval(function() {
        window.location.reload();
    }, 30000);
    @endif
});
</script>
@endpush
