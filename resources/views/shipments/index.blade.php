@extends('layouts.app')

@section('title', 'Status Pengiriman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-shipping-fast me-2"></i>Status Pengiriman</h4>
    <a href="{{ route('shipments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Pengiriman
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Kode Pengiriman</th>
                        <th>Distributor</th>
                        <th>Tanggal Order</th>
                        <th>Total Berat</th>
                        <th>Total Nilai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $shipment)
                    <tr>
                        <td><strong>{{ $shipment->shipment_code }}</strong></td>
                        <td>{{ $shipment->distributor->name }}</td>
                        <td>{{ $shipment->order_date->format('d/m/Y') }}</td>
                        <td>{{ number_format($shipment->total_weight, 2) }} kg</td>
                        <td>Rp {{ number_format($shipment->total_value, 0, ',', '.') }}</td>
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
                            <span class="badge bg-{{ $statusColors[$shipment->status] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($shipment->status !== 'delivered' && $shipment->status !== 'cancelled')
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $shipment->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Update Status -->
                    <div class="modal fade" id="updateStatusModal{{ $shipment->id }}" tabindex="-1">
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
                                            <label for="status{{ $shipment->id }}" class="form-label">Status Baru</label>
                                            <select class="form-select" id="status{{ $shipment->id }}" name="status" required>
                                                <option value="confirmed" {{ $shipment->status === 'pending' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="packed" {{ $shipment->status === 'confirmed' ? 'selected' : '' }}>Packed</option>
                                                <option value="shipped" {{ $shipment->status === 'packed' ? 'selected' : '' }}>Shipped</option>
                                                <option value="in_transit" {{ $shipment->status === 'shipped' ? 'selected' : '' }}>In Transit</option>
                                                <option value="delivered" {{ $shipment->status === 'in_transit' ? 'selected' : '' }}>Delivered</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description{{ $shipment->id }}" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="description{{ $shipment->id }}" name="description" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="location{{ $shipment->id }}" class="form-label">Lokasi</label>
                                            <input type="text" class="form-control" id="location{{ $shipment->id }}" name="location">
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
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data pengiriman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $shipments->links() }}
        </div>
    </div>
</div>
@endsection
