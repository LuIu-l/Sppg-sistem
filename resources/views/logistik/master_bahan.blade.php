@extends('layouts.app')

@section('title', 'Master Bahan Makanan')
@section('page-title', 'Master Data Bahan Makanan')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Master Bahan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('logistik.store-bahan') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Bahan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_bahan" class="form-control @error('nama_bahan') is-invalid @enderror" value="{{ old('nama_bahan') }}" required placeholder="Contoh: Beras Putih">
                        @error('nama_bahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Satuan Pengukuran <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" value="{{ old('satuan') }}" required placeholder="Contoh: Kg, Liter, Pcs">
                        @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>



                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Simpan Master Bahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-table text-primary me-2"></i>Daftar Master Bahan Makanan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nama Bahan</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bahanMakanan as $b)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $b->nama_bahan }}</td>
                                <td>{{ $b->satuan }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">Belum ada master data bahan makanan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($bahanMakanan->hasPages())
            <div class="card-footer bg-white py-3 border-top-0">
                {{ $bahanMakanan->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
