@extends('layouts.app')

@section('title', 'Edit Penerima')
@section('page-title', 'Edit Data Penerima Manfaat')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Penerima</h6>
                <span class="badge bg-secondary">{{ $penerima->kode_penerima }}</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('penerima.update', $penerima->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $penerima->nama) }}" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nik" class="form-label fw-semibold">NIK (16 Digit)</label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik', $penerima->nik) }}" maxlength="16">
                            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nisn" class="form-label fw-semibold">NISN (10 Digit)</label>
                            <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn', $penerima->nisn) }}" maxlength="10">
                            @error('nisn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_lahir" class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $penerima->tanggal_lahir->format('Y-m-d')) }}" required>
                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="L" {{ old('jenis_kelamin', $penerima->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $penerima->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12">
                            <label for="alamat" class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $penerima->alamat) }}</textarea>
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="institusi_id" class="form-label fw-semibold">Institusi / Sekolah <span class="text-danger">*</span></label>
                            <select class="form-select @error('institusi_id') is-invalid @enderror" id="institusi_id" name="institusi_id" required>
                                @foreach($institusiList as $inst)
                                    <option value="{{ $inst->id }}" {{ old('institusi_id', $penerima->institusi_id) == $inst->id ? 'selected' : '' }}>{{ $inst->nama_institusi }}</option>
                                @endforeach
                            </select>
                            @error('institusi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="pin" class="form-label fw-semibold">Ubah PIN Keamanan</label>
                            <input type="password" class="form-control @error('pin') is-invalid @enderror" id="pin" name="pin" maxlength="6">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah PIN</small>
                            @error('pin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12 mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $penerima->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Status Akun Aktif</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('penerima.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Perbarui Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
