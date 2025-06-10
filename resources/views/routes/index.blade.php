@extends('layouts.app')

@section('title', 'Daftar Rute')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Rute</h4>
                    <a href="{{ route('routes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Rute Baru
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($routes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Rute</th>
                                        <th>Nama Rute</th>
                                        <th>Jarak (KM)</th>
                                        <th>Estimasi Durasi</th>
                                        <th>Status</th>
                                        <th>Jadwal Pengiriman</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($routes as $index => $route)
                                        <tr>
                                            <td>{{ $routes->firstItem() + $index }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $route->code }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $route->name }}</strong>
                                                @if($route->description)
                                                    <br><small class="text-muted">{{ Str::limit($route->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ number_format($route->distance, 2) }} KM</td>
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
                                            </td>
                                            <td>
                                                @if($route->status == 'active')
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $route->deliverySchedules->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('routes.show', $route->id) }}"
                                                       class="btn btn-sm btn-outline-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('routes.edit', $route->id) }}"
                                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            title="Hapus" onclick="confirmDelete({{ $route->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $routes->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-route fa-3x text-muted mb-3"></i>
                            <h5>Belum ada rute</h5>
                            <p class="text-muted">Silakan tambah rute baru untuk memulai.</p>
                            <a href="{{ route('routes.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Rute Pertama
                            </a>
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
                <p>Apakah Anda yakin ingin menghapus rute ini?</p>
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
function confirmDelete(routeId) {
    const form = document.getElementById('deleteForm');
    form.action = `/routes/${routeId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
