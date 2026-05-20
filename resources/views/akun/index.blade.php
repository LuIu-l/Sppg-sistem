@extends('layouts.app')

@section('title', 'Manajemen Akun')
@section('page-title', 'Kelola Akun Staf Operasional')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-person-gear text-primary me-2"></i>Daftar Akun Pengguna</h6>
        
        <div class="d-flex gap-2 w-100" style="max-width: 500px;">
            <form action="{{ route('akun.index') }}" method="GET" class="d-flex gap-2 flex-grow-1">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama atau username..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary px-3"><i class="bi bi-search"></i></button>
                @if(request('search'))
                    <a href="{{ route('akun.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </form>
            <a href="{{ route('akun.create') }}" class="btn btn-sm btn-success text-nowrap"><i class="bi bi-plus-lg me-1"></i> Tambah Akun</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama Lengkap</th>
                        <th>Username</th>
                        <th>Peran (Role)</th>
                        <th>Institusi</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengguna as $p)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $p->nama }}</td>
                        <td>
                            <div>{{ $p->username }}</div>
                            <small class="text-muted">{{ $p->email ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $p->peran->nama_peran ?? 'Staf')) }}</span>
                        </td>
                        <td>{{ $p->institusi->nama_institusi ?? '-' }}</td>
                        <td>
                            @if($p->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Aktif</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Nonaktif</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <form action="{{ route('akun.toggle', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $p->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi {{ $p->is_active ? 'bi-power' : 'bi-check-circle' }}"></i>
                                    </button>
                                </form>
                                <a href="{{ route('akun.edit', $p->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                            Belum ada data akun pengguna.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pengguna->hasPages())
    <div class="card-footer bg-white py-3 border-top-0">
        {{ $pengguna->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
