@extends('layouts.app')

@section('title', 'Profil Pribadi')
@section('page-title', 'Profil Pribadi Staf')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-circle text-primary me-2"></i>Informasi Profil</h6>
            </div>
            <div class="card-body p-4">
            
                <div class="text-center mb-5">
                    <div class="position-relative d-inline-block mb-3">
                        @if($profil->foto_profil)
                            <img src="{{ asset('uploads/profil/' . $profil->foto_profil) }}" alt="Foto Profil" class="rounded-circle border border-3 border-light shadow" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle border border-3 border-white shadow d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="bi bi-person text-secondary" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column align-items-center">
                        @csrf
                        @method('PUT')
                        <div class="input-group mb-2" style="max-width: 350px;">
                            <input type="file" class="form-control form-control-sm @error('foto_profil') is-invalid @enderror" id="foto_profil" name="foto_profil" accept="image/jpeg,image/png,image/jpg" required>
                            <button class="btn btn-primary btn-sm px-3" type="submit"><i class="bi bi-upload me-1"></i> Unggah Foto</button>
                        </div>
                        @error('foto_profil')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text small">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                    </form>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3">Data Diri (Hanya Lihat)</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small mb-1">Username</label>
                        <input type="text" class="form-control bg-light" value="{{ $profil->username }}" readonly>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small mb-1">Nama Lengkap</label>
                        <input type="text" class="form-control bg-light" value="{{ $profil->nama }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small mb-1">Email</label>
                        <input type="text" class="form-control bg-light" value="{{ $profil->email }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small mb-1">Institusi / Unit</label>
                        <input type="text" class="form-control bg-light" value="{{ $profil->institusi->nama_institusi ?? 'Pusat SPPG' }}" readonly>
                    </div>
                </div>

                <div class="alert alert-info border-info-subtle border-0 shadow-sm small mb-0">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Peran Akses:</strong> Anda terdaftar sebagai <strong>{{ ucwords(str_replace('_', ' ', $profil->peran->nama_peran ?? 'Staf')) }}</strong>. Jika ada perubahan data diri seperti nama, email, atau kata sandi, silakan hubungi Kepala SPPG.
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
