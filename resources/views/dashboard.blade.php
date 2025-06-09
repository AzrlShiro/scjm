@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center border-primary">
            <div class="card-body">
                <i class="fas fa-store fa-2x text-primary mb-3"></i>
                <h4 class="text-primary">{{ \App\Models\Distributor::count() }}</h4>
                <p class="card-text">Total Distributor</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-info">
            <div class="card-body">
                <i class="fas fa-shipping-fast fa-2x text-info mb-3"></i>
                <h4 class="text-info">{{ \App\Models\Shipment::count() }}</h4>
                <p class="card-text">Total Pengiriman</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-warning">
            <div class="card-body">
                <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                <h4 class="text-warning">{{ \App\Models\Shipment::where('status', 'in_transit')->count() }}</h4>
                <p class="card-text">Dalam Perjalanan</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-success">
            <div class="card-body">
                <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                <h4 class="text-success">{{ \App\Models\Shipment::where('status', 'delivered')->count() }}</h4>
                <p class="card-text">Terkirim</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line me-2"></i>Pengiriman Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode Pengiriman</th>
                                <th>Distributor</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Shipment::with('distributor')->latest()->take(5)->get() as $shipment)
                            <tr>
                                <td>{{ $shipment->shipment_code }}</td>
                                <td>{{ $shipment->distributor->name }}</td>
                                <td>{{ $shipment->order_date->format('d/m/Y') }}</td>
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
                                        {{ ucfirst($shipment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-calendar-alt me-2"></i>Jadwal Hari Ini</h5>
            </div>
            <div class="card-body">
                @php
                    $todaySchedules = \App\Models\DeliverySchedule::whereDate('delivery_date', today())->with('route')->get();
                @endphp
                @forelse($todaySchedules as $schedule)
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                    <div>
                        <strong>{{ $schedule->route->name }}</strong><br>
                        <small class="text-muted">{{ $schedule->departure_time->format('H:i') }}</small>
                    </div>
                    <span class="badge bg-{{ $schedule->status === 'scheduled' ? 'warning' : 'success' }}">
                        {{ ucfirst($schedule->status) }}
                    </span>
                </div>
                @empty
                <p class="text-muted text-center">Tidak ada jadwal hari ini</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
