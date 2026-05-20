@extends('layouts.app')

@section('title', 'Buat Menu Baru')
@section('page-title', 'Perencanaan Menu Gizi Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-journal-plus text-primary me-2"></i>Form Buat Menu Gizi</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('menu.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label for="nama_menu" class="form-label fw-semibold">Nama Menu Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_menu') is-invalid @enderror" id="nama_menu" name="nama_menu" value="{{ old('nama_menu') }}" placeholder="Contoh: Nasi Putih + Ayam Bakar + Sayur Sop" required>
                            @error('nama_menu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="tanggal_berlaku" class="form-label fw-semibold">Mulai Berlaku <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_berlaku') is-invalid @enderror" id="tanggal_berlaku" name="tanggal_berlaku" value="{{ old('tanggal_berlaku', today()->format('Y-m-d')) }}" required>
                            @error('tanggal_berlaku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Kandungan Gizi (Per Porsi)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-sm-6">
                            <label for="total_kalori" class="form-label small fw-semibold">Kalori (kkal) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('total_kalori') is-invalid @enderror" id="total_kalori" name="total_kalori" value="{{ old('total_kalori', 0) }}" required>
                            @error('total_kalori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label for="total_protein" class="form-label small fw-semibold">Protein (gram) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('total_protein') is-invalid @enderror" id="total_protein" name="total_protein" value="{{ old('total_protein', 0) }}" required>
                            @error('total_protein')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label for="total_karbohidrat" class="form-label small fw-semibold">Karbohidrat (gram) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('total_karbohidrat') is-invalid @enderror" id="total_karbohidrat" name="total_karbohidrat" value="{{ old('total_karbohidrat', 0) }}" required>
                            @error('total_karbohidrat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label for="total_lemak" class="form-label small fw-semibold">Lemak (gram) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('total_lemak') is-invalid @enderror" id="total_lemak" name="total_lemak" value="{{ old('total_lemak', 0) }}" required>
                            @error('total_lemak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label fw-semibold">Catatan / Deskripsi (Opsional)</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3" placeholder="Contoh: Aman untuk anak alergi makanan laut.">{{ old('catatan') }}</textarea>
                        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="alert alert-info border-info-subtle">
                        <i class="bi bi-info-circle me-2"></i> Menu yang dibuat akan berstatus <strong>Menunggu Persetujuan</strong> dan harus disetujui oleh Kepala SPPG sebelum bisa didistribusikan.
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('menu.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan & Ajukan Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
