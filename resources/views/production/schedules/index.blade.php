@extends('layouts.app')

@section('title', 'Daftar Jadwal Produksi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Jadwal Produksi</h4>
                    <div class="btn-group">
                        <a href="{{ route('production.schedules.create') }}" class="btn btn-primary me-2">
                            <i class="fas fa-plus"></i> Tambah Jadwal Baru
                        </a>
                        <form action="{{ route('production.schedules.generateFromLowStock') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-magic"></i> Buat Jadwal dari Stok Rendah
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif


                    @if($schedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Produk Jamu</th>
                                        <th>Resep</th>
                                        <th>Tanggal Dijadwalkan</th>
                                        <th>Kuantitas (Unit)</th>
                                        <th>Jumlah Batch</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules as $index => $schedule)
                                        <tr>
                                            <td>{{ $schedules->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $schedule->jamuProduct->name ?? 'N/A' }}</strong>
                                                <br><small class="text-muted">{{ $schedule->jamuProduct->code ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $schedule->recipe->name ?? 'N/A' }}</strong>
                                                <br><small class="text-muted">{{ $schedule->recipe->code ?? 'N/A' }}</small>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}</td>
                                            <td>{{ number_format($schedule->planned_quantity, 0) }} Unit</td>
                                            <td>{{ $schedule->planned_batches }}</td>
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
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('production.schedules.show', $schedule->id) }}"
                                                       class="btn btn-sm btn-outline-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('production.schedules.edit', $schedule->id) }}"
                                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            title="Hapus" onclick="confirmDelete({{ $schedule->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $schedules->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
                            <h5>Belum ada jadwal produksi</h5>
                            <p class="text-muted">Silakan tambah jadwal produksi baru untuk memulai.</p>
                            <a href="{{ route('production.schedules.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Jadwal Produksi Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus jadwal produksi ini?</p>
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
    form.action = `/production-schedules/${scheduleId}`; // Sesuaikan dengan route name jika perlu

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
