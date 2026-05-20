@extends('layouts.app')

@section('title', 'Stok Gudang')
@section('page-title', 'Manajemen Stok Bahan Makanan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0 fw-bold">Informasi Gudang</h5>
                    <p class="mb-0 small opacity-75">Kelola dan pantau stok bahan makanan untuk program gizi.</p>
                </div>
                <div class="text-end">
                    <h3 class="fw-bold mb-0 text-warning">{{ $jumlahKritis ?? 0 }}</h3>
                    <small>Item Stok Kritis</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-archive text-primary me-2"></i>Daftar Stok Bahan Makanan</h6>
        
        <div class="d-flex gap-2 w-100" style="max-width: 500px;">
            <form action="{{ route('logistik.index') }}" method="GET" class="d-flex gap-2 flex-grow-1">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari bahan makanan..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary px-3"><i class="bi bi-search"></i></button>
                @if(request('search'))
                    <a href="{{ route('logistik.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </form>
            <a href="{{ route('logistik.master') }}" class="btn btn-sm btn-outline-secondary text-nowrap"><i class="bi bi-database me-1"></i> Master Data</a>
            <a href="{{ route('logistik.form') }}" class="btn btn-sm btn-success text-nowrap"><i class="bi bi-plus-lg me-1"></i> Tambah Stok</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama Bahan</th>
                        <th>Stok Aktual</th>
                        <th>Batas Minimum</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokList as $stok)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $stok->bahanMakanan->nama_bahan ?? '-' }}</td>
                        <td>
                            <div class="fs-5 fw-bold {{ $stok->is_kritis ? 'text-danger' : 'text-dark' }}">
                                {{ $stok->stok_aktual }} <span class="fs-6 text-muted fw-normal">{{ $stok->bahanMakanan->satuan ?? '' }}</span>
                            </div>
                        </td>
                        <td>{{ $stok->bahanMakanan->stok_minimum ?? 0 }} {{ $stok->bahanMakanan->satuan ?? '' }}</td>
                        <td>
                            @if($stok->is_kritis)
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Kritis</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Aman</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('logistik.form', $stok->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-circle"></i> Tambah Stok</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-archive fs-2 d-block mb-2"></i>
                            Gudang masih kosong. Belum ada stok yang diinput.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($stokList->hasPages())
    <div class="card-footer bg-white py-3 border-top-0">
        {{ $stokList->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
