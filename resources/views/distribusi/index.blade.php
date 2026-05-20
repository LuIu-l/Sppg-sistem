@extends('layouts.app')

@section('title', 'Data Distribusi')
@section('page-title', 'Manajemen Distribusi Hari Ini')

@section('content')

{{-- Alert Notifikasi --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam text-primary me-2"></i>Daftar Distribusi Hari Ini</h6>
        <div>
            <a href="{{ route('distribusi.scan') }}" class="btn btn-sm btn-primary"><i class="bi bi-qr-code-scan me-1"></i> Scan QR Baru</a>
            <a href="{{ route('distribusi.riwayat') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-clock-history me-1"></i> Riwayat Lengkap</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Waktu</th>
                        <th>Penerima Manfaat</th>
                        <th>Menu Gizi</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th class="pe-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distribusiHariIni as $d)
                    <tr>
                        <td class="ps-4">{{ $d->waktu_distribusi->format('H:i') }} WIB</td>
                        <td>
                            <div class="fw-semibold">{{ $d->penerima->nama ?? '-' }}</div>
                            <small class="text-muted font-monospace">{{ $d->penerima->kode_penerima ?? '-' }}</small>
                        </td>
                        <td>{{ $d->jadwal->menuGizi->nama_menu ?? '-' }}</td>
                        <td>{{ $d->petugas->nama ?? '-' }}</td>
                        <td>
                            @if($d->status == 'terdistribusi')
                                <span class="badge bg-success">Terdistribusi</span>
                            @else
                                <span class="badge bg-danger">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="pe-4 text-center">
                            @if($d->status == 'terdistribusi')
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBatalkan"
                                    data-id="{{ $d->id }}"
                                    data-nama="{{ $d->penerima->nama ?? '-' }}"
                                    title="Batalkan distribusi ini">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Batalkan
                                </button>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada distribusi yang dilakukan hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($distribusiHariIni->hasPages())
    <div class="card-footer bg-white py-3 border-top-0">
        {{ $distribusiHariIni->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- Modal Konfirmasi Batalkan --}}
<div class="modal fade" id="modalBatalkan" tabindex="-1" aria-labelledby="modalBatalkanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="modalBatalkanLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Pembatalan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-1">Anda akan membatalkan distribusi atas nama:</p>
                <p class="fw-bold fs-5 text-danger mb-3" id="namaPenerima">—</p>
                <div class="alert alert-warning border-0 small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Tindakan ini akan mengubah status dari <strong>Terdistribusi</strong> menjadi <strong>Dibatalkan</strong> dan mengembalikan stok bahan secara otomatis.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="formBatalkan" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Ya, Batalkan Distribusi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    const modalBatalkan = document.getElementById('modalBatalkan');
    modalBatalkan.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');

        document.getElementById('namaPenerima').textContent = nama;
        document.getElementById('formBatalkan').action = `/distribusi/${id}/batalkan`;
    });
</script>
@endsection

@endsection

