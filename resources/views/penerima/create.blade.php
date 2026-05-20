@extends('layouts.app')

@section('title', 'Tambah Penerima')
@section('page-title', 'Pendaftaran Penerima Manfaat Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus text-primary me-2"></i>Form Tambah Penerima Manfaat</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('penerima.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nik" class="form-label fw-semibold">NIK (16 Digit)</label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" maxlength="16">
                            <small class="text-muted">Kosongkan jika tidak ada</small>
                            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nisn" class="form-label fw-semibold">NISN (10 Digit)</label>
                            <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn') }}" maxlength="10">
                            <small class="text-muted">Wajib diisi untuk siswa sekolah</small>
                            @error('nisn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_lahir" class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12">
                            <label for="alamat" class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="institusi_id" class="form-label fw-semibold">Institusi / Sekolah <span class="text-danger">*</span></label>
                            <select class="form-select @error('institusi_id') is-invalid @enderror" id="institusi_id" name="institusi_id" required>
                                <option value="">-- Pilih Institusi --</option>
                                @foreach($institusiList as $inst)
                                    <option value="{{ $inst->id }}" {{ old('institusi_id') == $inst->id ? 'selected' : '' }}>{{ $inst->nama_institusi }}</option>
                                @endforeach
                            </select>
                            @error('institusi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="pin" class="form-label fw-semibold">PIN Keamanan (4-6 Angka) <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('pin') is-invalid @enderror" id="pin" name="pin" required maxlength="6">
                            <small class="text-muted">Akan digunakan penerima untuk login</small>
                            @error('pin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('penerima.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
