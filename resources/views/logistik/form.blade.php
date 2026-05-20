@extends('layouts.app')

@section('title', 'Tambah Stok')
@section('page-title', 'Form Penambahan Stok Gudang')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-down text-primary me-2"></i>Tambah Stok Bahan Makanan</h6>
                @if($stok)
                    <span class="badge bg-info text-dark">Penambahan Stok Lama</span>
                @else
                    <span class="badge bg-primary">Input Stok Baru</span>
                @endif
            </div>
            <div class="card-body p-4">
                <form action="{{ route('logistik.tambah') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="bahan_makanan_id" class="form-label fw-semibold">Pilih Bahan Makanan <span class="text-danger">*</span></label>
                        <select name="bahan_makanan_id" id="bahan_makanan_id" class="form-select @error('bahan_makanan_id') is-invalid @enderror" required {{ $stok ? 'readonly' : '' }}>
                            @if(!$stok)
                                <option value="">-- Cari Bahan --</option>
                            @endif
                            
                            @foreach($bahanMakananList as $b)
                                @if($stok && $stok->bahan_makanan_id == $b->id)
                                    <option value="{{ $b->id }}" selected>{{ $b->nama_bahan }} ({{ $b->satuan }})</option>
                                @elseif(!$stok)
                                    <option value="{{ $b->id }}">{{ $b->nama_bahan }} ({{ $b->satuan }})</option>
                                @endif
                            @endforeach
                        </select>
                        @error('bahan_makanan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if(!$stok)
                            <div class="form-text">Jika bahan tidak ada, silakan tambahkan dulu di <a href="{{ route('logistik.master') }}">Master Data Bahan</a>.</div>
                        @endif
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="jumlah_ditambahkan" class="form-label fw-semibold">Jumlah yang Ditambahkan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('jumlah_ditambahkan') is-invalid @enderror" id="jumlah_ditambahkan" name="jumlah_ditambahkan" value="{{ old('jumlah_ditambahkan') }}" required min="0.01">
                                <span class="input-group-text bg-light" id="satuan-text">-</span>
                            </div>
                            @error('jumlah_ditambahkan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @if($stok)
                                <div class="form-text text-primary mt-2"><i class="bi bi-info-circle me-1"></i> Stok saat ini: <strong>{{ $stok->stok_aktual }}</strong></div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="stok_minimum" class="form-label fw-semibold">Batas Stok Minimum (Peringatan)</label>
                            <input type="number" step="0.01" class="form-control @error('stok_minimum') is-invalid @enderror" id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', $stok->stok_minimum ?? 0) }}" min="0">
                            @error('stok_minimum')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan / Sumber (Opsional)</label>
                        <input type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Pembelian dari Supplier A">
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('logistik.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Proses Tambah Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
