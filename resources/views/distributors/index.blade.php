@extends('layouts.app')

@section('title', 'Data Distributor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-store me-2"></i>Data Distributor</h4>
    <a href="{{ route('distributors.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Distributor
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kota</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distributors as $distributor)
                    <tr>
                        <td><strong>{{ $distributor->code }}</strong></td>
                        <td>{{ $distributor->name }}</td>
                        <td>{{ $distributor->city }}, {{ $distributor->province }}</td>
                        <td>{{ $distributor->phone }}</td>
                        <td>
                            <span class="badge bg-{{ $distributor->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($distributor->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('distributors.show', $distributor) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('distributors.edit', $distributor) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('distributors.destroy', $distributor) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data distributor</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $distributors->links() }}
        </div>
    </div>
</div>
@endsection
