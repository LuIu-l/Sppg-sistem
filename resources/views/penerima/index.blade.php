@extends('layouts.app')

@section('title', 'Penerima Manfaat')
@section('page-title', 'Kelola Penerima Manfaat')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-people text-primary me-2"></i>Daftar Penerima Manfaat</h6>
        
        <div class="d-flex gap-2 w-100" style="max-width: 500px;">
            <form action="{{ route('penerima.index') }}" method="GET" class="d-flex gap-2 flex-grow-1">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama, NIK, NISN, atau Kode..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary px-3"><i class="bi bi-search"></i></button>
                @if(request('search'))
                    <a href="{{ route('penerima.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </form>
            @if(session('pengguna.role') === 'kepala_sppg')
            <a href="{{ route('penerima.create') }}" class="btn btn-sm btn-success text-nowrap"><i class="bi bi-plus-lg me-1"></i> Tambah Data</a>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode / Nama</th>
                        <th>Identitas (NIK/NISN)</th>
                        <th>Institusi</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penerima as $p)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $p->nama }}</div>
                            <small class="text-muted font-monospace">{{ $p->kode_penerima }}</small>
                        </td>
                        <td>
                            @if($p->nik)<div class="small">NIK: <span class="font-monospace">{{ $p->nik }}</span></div>@endif
                            @if($p->nisn)<div class="small">NISN: <span class="font-monospace">{{ $p->nisn }}</span></div>@endif
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
                            @if(session('pengguna.role') === 'kepala_sppg')
                            <a href="{{ route('penerima.edit', $p->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                            Belum ada data penerima manfaat yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($penerima->hasPages())
    <div class="card-footer bg-white py-3 border-top-0">
        {{ $penerima->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
