@extends('layouts.app')

@section('title', 'Detail Jadwal Produksi: #' . $schedule->id)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Jadwal Produksi: #{{ $schedule->id }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('production.schedules.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                        <a href="{{ route('production.schedules.edit', $schedule->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Jadwal
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $schedule->id }})">
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
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Dasar</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>ID Jadwal:</strong></td>
                                            <td><span class="badge bg-primary">#{{ $schedule->id }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Produk Jamu:</strong></td>
                                            <td><strong>{{ $schedule->jamuProduct->name ?? 'N/A' }}</strong> (<small>{{ $schedule->jamuProduct->code ?? 'N/A' }}</small>)</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Resep:</strong></td>
                                            <td><strong>{{ $schedule->recipe->name ?? 'N/A' }}</strong> (<small>{{ $schedule->recipe->code ?? 'N/A' }}</small>)</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Dijadwalkan:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kuantitas Direncanakan:</strong></td>
                                            <td>{{ number_format($schedule->planned_quantity, 0) }} Unit</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jumlah Batch Direncanakan:</strong></td>
                                            <td>{{ $schedule->planned_batches }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
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
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Catatan & Info Tambahan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Catatan:</strong>
                                        <div class="mt-2">
                                            @if($schedule->notes)
                                                <div class="alert alert-light">
                                                    {{ $schedule->notes }}
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
                                            <td>{{ $schedule->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Terakhir diperbarui:</strong></td>
                                            <td>{{ $schedule->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jumlah Batch Produksi Aktual:</strong></td>
                                            <td><span class="badge bg-info">{{ $schedule->productionBatches->count() }}</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-boxes"></i> Kebutuhan Bahan Baku</h5>
                        </div>
                        <div class="card-body">
                            @if(count($materialNeeds) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Bahan Baku</th>
                                                <th>Dibutuhkan per Batch (Unit)</th>
                                                <th>Total Dibutuhkan (Unit)</th>
                                                <th>Stok Tersedia (Unit)</th>
                                                <th>Kekurangan (Unit)</th>
                                                <th>Status Stok</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($materialNeeds as $need)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $need['material']->name ?? 'N/A' }}</strong>
                                                        <br><small class="text-muted">{{ $need['material']->unit ?? 'N/A' }}</small>
                                                    </td>
                                                    <td>{{ number_format($need['needed_per_batch'], 2) }}</td>
                                                    <td><strong>{{ number_format($need['total_needed'], 2) }}</strong></td>
                                                    <td>{{ number_format($need['available_stock'], 2) }}</td>
                                                    <td>
                                                        @if($need['shortage'] > 0)
                                                            <span class="text-danger"><strong>{{ number_format($need['shortage'], 2) }}</strong></span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($need['shortage'] > 0)
                                                            <span class="badge bg-danger">Kurang</span>
                                                        @else
                                                            <span class="badge bg-success">Cukup</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-exclamation-circle fa-2x text-warning mb-2"></i>
                                    <p class="text-muted">Tidak ada data kebutuhan bahan baku atau resep tidak memiliki bahan baku.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($schedule->productionBatches->count() > 0)
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-boxes"></i> Daftar Batch Produksi ({{ $schedule->productionBatches->count() }})</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Kode Batch</th>
                                                <th>Tanggal Produksi</th>
                                                <th>Kuantitas Aktual</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($schedule->productionBatches as $index => $batch)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><span class="badge bg-primary">{{ $batch->batch_code }}</span></td>
                                                    <td>{{ \Carbon\Carbon::parse($batch->production_date)->format('d/m/Y') }}</td>
                                                    <td>{{ number_format($batch->actual_quantity, 0) }} Unit</td>
                                                    <td>
                                                        @switch($batch->status)
                                                            @case('pending')
                                                                <span class="badge bg-warning">Menunggu</span>
                                                                @break
                                                            @case('in_progress')
                                                                <span class="badge bg-primary">Berlangsung</span>
                                                                @break
                                                            @case('completed')
                                                                <span class="badge bg-success">Selesai</span>
                                                                @break
                                                            @case('failed')
                                                                <span class="badge bg-danger">Gagal</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ ucfirst($batch->status) }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        {{-- Asumsi ada route untuk detail production batch --}}
                                                        {{-- <a href="{{ route('production.batches.show', $batch->id) }}" --}}
                                                        {{--    class="btn btn-sm btn-outline-info" title="Detail Batch"> --}}
                                                        {{--     <i class="fas fa-eye"></i> --}}
                                                        {{-- </a> --}}
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
                                <h5 class="mb-0"><i class="fas fa-boxes"></i> Daftar Batch Produksi</h5>
                            </div>
                            <div class="card-body text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <h5>Belum ada batch produksi</h5>
                                <p class="text-muted">Jadwal ini belum memiliki batch produksi yang terkait.</p>
                                {{-- Jika ada fitur untuk menambah batch dari sini, tambahkan link berikut: --}}
                                {{-- <a href="{{ route('production.batches.create') }}?schedule_id={{ $schedule->id }}" class="btn btn-primary"> --}}
                                {{--     <i class="fas fa-plus"></i> Tambah Batch Produksi --}}
                                {{-- </a> --}}
                            </div>
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
                @if($schedule->productionBatches->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian!</strong> Jadwal ini memiliki {{ $schedule->productionBatches->count() }} batch produksi terkait. Jika dihapus, semua batch produksi yang terkait juga akan terhapus.
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
    form.action = `/production-schedules/${scheduleId}`; // Sesuaikan dengan route name jika perlu

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
