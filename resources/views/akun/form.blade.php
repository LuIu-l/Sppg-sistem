@extends('layouts.app')

@section('title', $akun ? 'Edit Akun' : 'Tambah Akun')
@section('page-title', $akun ? 'Edit Akun Pengguna' : 'Pendaftaran Akun Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi {{ $akun ? 'bi-pencil-square' : 'bi-person-plus' }} text-primary me-2"></i>Form {{ $akun ? 'Edit' : 'Tambah' }} Akun Staf</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ $akun ? route('akun.update', $akun->id) : route('akun.store') }}" method="POST">
                    @csrf
                    @if($akun) @method('PUT') @endif
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $akun->nama ?? '') }}" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $akun->username ?? '') }}" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email Valid <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $akun->email ?? '') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        @if(!($akun && $akun->id == 1))
                        <div class="col-md-6">
                            <label for="peran_id" class="form-label fw-semibold">Peran (Role) <span class="text-danger">*</span></label>
                            <select class="form-select @error('peran_id') is-invalid @enderror" id="peran_id" name="peran_id" required>
                                <option value="">-- Pilih Peran --</option>
                                @foreach($peranList as $peran)
                                    <option value="{{ $peran->id }}" {{ old('peran_id', $akun->peran_id ?? '') == $peran->id ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $peran->nama_peran)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('peran_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="institusi_id" class="form-label fw-semibold">Institusi / Unit</label>
                            <select class="form-select @error('institusi_id') is-invalid @enderror" id="institusi_id" name="institusi_id">
                                <option value="">-- Tidak Spesifik Institusi --</option>
                                @foreach($institusiList as $inst)
                                    <option value="{{ $inst->id }}" {{ old('institusi_id', $akun->institusi_id ?? '') == $inst->id ? 'selected' : '' }}>
                                        {{ $inst->nama_institusi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('institusi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @else
                        <input type="hidden" name="peran_id" value="{{ $akun->peran_id }}">
                        <input type="hidden" name="institusi_id" value="{{ $akun->institusi_id ?? '' }}">
                        @endif

                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">Password {{ $akun ? '(Opsional)' : '*' }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" {{ $akun ? '' : 'required' }} minlength="8">
                            @if($akun)<small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>@endif
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password {{ $akun ? '(Opsional)' : '*' }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ $akun ? '' : 'required' }}>
                        </div>

                        @if($akun)
                        <div class="col-md-12 mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $akun->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Status Akun Aktif</label>
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('akun.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
