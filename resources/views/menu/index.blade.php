@extends('layouts.app')

@section('title', 'Menu Gizi')
@section('page-title', 'Manajemen Menu Gizi')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-journal-richtext text-primary me-2"></i>Daftar Menu Gizi</h6>
        
        <div class="d-flex gap-2 w-100" style="max-width: 400px;">
            <form action="{{ route('menu.index') }}" method="GET" class="d-flex gap-2 flex-grow-1">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>
            <a href="{{ route('menu.create') }}" class="btn btn-sm btn-success text-nowrap"><i class="bi bi-plus-lg me-1"></i> Buat Menu</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama Menu</th>
                        <th>Kandungan (Kal/Pro/Kar/Lem)</th>
                        <th>Tgl Berlaku</th>
                        <th>Pembuat</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menu as $m)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">{{ $m->nama_menu }}</td>
                        <td>
                            <small class="text-muted">
                                {{ $m->total_kalori }} kkal | {{ $m->total_protein }}g | {{ $m->total_karbohidrat }}g | {{ $m->total_lemak }}g
                            </small>
                        </td>
                        <td>{{ $m->tanggal_berlaku->format('d/m/Y') }}</td>
                        <td>{{ $m->pembuat->nama ?? '-' }}</td>
                        <td>
                            @if($m->status == 'disetujui')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Disetujui</span>
                            @elseif($m->status == 'ditolak')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Ditolak</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-50">Menunggu</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                @if(session('pengguna.role') === 'kepala_sppg' && $m->status === 'menunggu')
                                    <form action="{{ route('menu.setujui', $m->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $m->id }}" title="Tolak"><i class="bi bi-x-lg"></i></button>
                                @endif
                                
                                <a href="{{ route('menu.edit', $m->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                            </div>

                            <!-- Modal Penolakan -->
                            <div class="modal fade" id="modalTolak{{ $m->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content text-start">
                                        <form action="{{ route('menu.tolak', $m->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak Menu: {{ $m->nama_menu }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Catatan Penolakan <span class="text-danger">*</span></label>
                                                    <textarea name="catatan_penolakan" class="form-control" required rows="3" placeholder="Alasan menu ini ditolak..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Tolak Menu</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                            Belum ada data menu gizi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($menu->hasPages())
    <div class="card-footer bg-white py-3 border-top-0">
        {{ $menu->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
