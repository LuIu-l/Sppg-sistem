@extends('layouts.app')

@section('title', 'Riwayat Distribusi')
@section('page-title', 'Riwayat Keseluruhan Distribusi')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Distribusi</h6>
        
        <form action="{{ route('distribusi.riwayat') }}" method="GET" class="d-flex gap-2 w-100" style="max-width: 500px;">
            <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama/kode penerima..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-primary px-3"><i class="bi bi-search"></i></button>
            @if(request('search') || request('tanggal'))
                <a href="{{ route('distribusi.riwayat') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tanggal & Waktu</th>
                        <th>Penerima Manfaat</th>
                        <th>Menu Gizi</th>
                        <th>Petugas</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $d)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $d->waktu_distribusi->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $d->waktu_distribusi->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $d->penerima->nama ?? '-' }}</div>
                            <small class="text-muted font-monospace">{{ $d->penerima->kode_penerima ?? '-' }}</small>
                        </td>
                        <td>{{ $d->jadwal->menuGizi->nama_menu ?? '-' }}</td>
                        <td>{{ $d->petugas->nama ?? '-' }}</td>
                        <td class="pe-4">
                            @if($d->status == 'terdistribusi')
                                <span class="badge bg-success">Terdistribusi</span>
                            @else
                                <span class="badge bg-danger">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-2 d-block mb-2"></i>
                            Tidak ada data riwayat distribusi yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($riwayat->hasPages())
    <div class="card-footer bg-white py-3 border-top-0">
        {{ $riwayat->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
