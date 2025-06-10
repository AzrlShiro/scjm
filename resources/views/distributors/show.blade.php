@extends('layouts.app')

@section('title', 'Detail Distributor')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-eye me-2"></i>Detail Distributor</h5>
                <span class="badge bg-{{ $distributor->status === 'active' ? 'success' : 'secondary' }} fs-6">
                    {{ ucfirst($distributor->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Informasi Dasar</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="30%" class="fw-bold">Kode:</td>
                                <td>{{ $distributor->code }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nama:</td>
                                <td>{{ $distributor->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Telepon:</td>
                                <td>
                                    <a href="tel:{{ $distributor->phone }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $distributor->phone }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td>
                                    @if($distributor->email)
                                        <a href="mailto:{{ $distributor->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope me-1"></i>{{ $distributor->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Alamat</h6>
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                <strong>{{ $distributor->address }}</strong>
                            </p>
                            <p class="mb-1">{{ $distributor->city }}, {{ $distributor->province }}</p>
                            <p class="mb-0 text-muted">{{ $distributor->postal_code }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-muted mb-2">Informasi Sistem</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-plus me-1"></i>
                                    Dibuat: {{ $distributor->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-edit me-1"></i>
                                    Diperbarui: {{ $distributor->updated_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('distributors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <div>
                        <a href="{{ route('distributors.edit', $distributor) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <form action="{{ route('distributors.destroy', $distributor) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus distributor ini?')">
                                <i class="fas fa-trash me-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
