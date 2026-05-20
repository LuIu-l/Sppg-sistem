@extends('layouts.app')

@section('title', 'Scan QR Distribusi')
@section('page-title', 'Konfirmasi Distribusi via QR Code')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">

        {{-- Alert sesi --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i><strong>Gagal!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white py-3 rounded-top-4 border-bottom-0">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-camera-video text-primary me-2"></i>Kamera Scanner</h6>
                    </div>
                    <div class="card-body p-4 text-center">

                        {{-- Viewport kamera QR --}}
                        <div id="qr-reader-container" class="mb-3">
                            <div id="qr-reader" style="width: 100%; border-radius: 12px; overflow: hidden;"></div>
                            {{-- Placeholder sebelum kamera aktif --}}
                            <div id="qr-placeholder" class="d-flex flex-column align-items-center justify-content-center bg-light rounded-4 p-5" style="min-height: 260px;">
                                <i class="bi bi-qr-code-scan fs-1 text-primary mb-3"></i>
                                <p class="text-muted mb-0">Tekan tombol <strong>"Aktifkan Kamera"</strong><br>untuk mulai scan QR Code</p>
                            </div>
                        </div>

                        {{-- Kontrol kamera --}}
                        <div class="d-grid gap-2">
                            <button id="btn-start-scan" class="btn btn-primary rounded-3 fw-semibold py-2">
                                <i class="bi bi-camera-fill me-2"></i>Aktifkan Kamera
                            </button>
                            <button id="btn-stop-scan" class="btn btn-outline-secondary rounded-3 fw-semibold py-2" style="display:none;">
                                <i class="bi bi-camera-video-off me-2"></i>Matikan Kamera
                            </button>
                        </div>

                        {{-- Status hasil scan --}}
                        <div id="scan-result-badge" class="mt-3" style="display:none;">
                            <div class="alert border-0 rounded-3 mb-0 fw-semibold" id="scan-result-alert">
                                <i id="scan-result-icon" class="me-2"></i>
                                <span id="scan-result-text"></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white py-3 rounded-top-4 border-bottom-0">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-clipboard2-check text-success me-2"></i>Konfirmasi Distribusi</h6>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">

                        {{-- Pilih Jadwal --}}
                        <div class="mb-4">
                            <label for="jadwal_display" class="form-label fw-semibold text-muted small text-uppercase tracking-1">Jadwal Aktif Hari Ini</label>
                            @isset($jadwalHariIni)
                                <div class="d-flex align-items-center bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 p-3">
                                    <i class="bi bi-calendar-check-fill text-success fs-4 me-3"></i>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $jadwalHariIni->menuGizi->nama_menu ?? 'Menu Hari Ini' }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($jadwalHariIni->tanggal_distribusi)->locale('id')->isoFormat('dddd, D MMMM Y') }}</small>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3 p-3">
                                    <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-3"></i>
                                    <div class="fw-semibold text-dark">Tidak ada jadwal distribusi untuk hari ini.</div>
                                </div>
                            @endisset
                        </div>

                        {{-- Form submit --}}
                        <form method="POST" action="{{ route('distribusi.konfirmasi') }}" id="form-distribusi">
                            @csrf

                            {{-- Hidden: jadwal_distribusi_id --}}
                            <input type="hidden" name="jadwal_distribusi_id" value="{{ $jadwalHariIni->id ?? '' }}">

                            {{-- Input kode penerima (diisi otomatis dari scan) --}}
                            <div class="mb-4">
                                <label for="kode_penerima" class="form-label fw-semibold text-muted small text-uppercase">Kode Penerima</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-qr-code text-secondary"></i>
                                    </span>
                                    <input type="text"
                                           name="kode_penerima"
                                           id="kode_penerima"
                                           class="form-control border-start-0 font-monospace @error('kode_penerima') is-invalid @enderror"
                                           placeholder="Scan QR atau ketik kode..."
                                           value="{{ old('kode_penerima') }}"
                                           required>
                                    @error('kode_penerima')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text"><i class="bi bi-info-circle me-1"></i>Kode otomatis terisi saat QR berhasil dipindai.</div>
                            </div>

                            {{-- Tombol konfirmasi --}}
                            @if(isset($jadwalHariIni))
                            <div class="d-grid mt-auto">
                                <button type="submit" id="btn-konfirmasi" class="btn btn-success btn-lg rounded-3 fw-bold py-3">
                                    <i class="bi bi-check2-circle me-2 fs-5"></i>Konfirmasi Distribusi
                                </button>
                            </div>
                            @else
                            <div class="d-grid mt-auto">
                                <button type="button" class="btn btn-secondary btn-lg rounded-3 fw-bold py-3" disabled>
                                    <i class="bi bi-slash-circle me-2"></i>Tidak Ada Jadwal Aktif
                                </button>
                            </div>
                            @endif
                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- Panduan --}}
        <div class="alert alert-info border-0 shadow-sm rounded-4 mt-4">
            <h6 class="alert-heading fw-bold"><i class="bi bi-info-circle me-2"></i>Panduan Distribusi</h6>
            <div class="row small mb-0">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="d-flex align-items-start"><i class="bi bi-1-circle-fill text-info me-2 mt-1"></i><span>Arahkan kamera ke QR Code pada layar HP penerima manfaat.</span></div>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="d-flex align-items-start"><i class="bi bi-2-circle-fill text-info me-2 mt-1"></i><span>Kode akan otomatis terisi. Klik <strong>Konfirmasi Distribusi</strong>.</span></div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-start"><i class="bi bi-3-circle-fill text-info me-2 mt-1"></i><span>Sistem mencegah <strong>pengambilan ganda</strong> di hari yang sama secara otomatis.</span></div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
{{-- START: QR Code Scanner — menggunakan library html5-qrcode --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const html5QrCode       = new Html5Qrcode("qr-reader");
    const btnStart          = document.getElementById('btn-start-scan');
    const btnStop           = document.getElementById('btn-stop-scan');
    const placeholder       = document.getElementById('qr-placeholder');
    const inputKode         = document.getElementById('kode_penerima');
    const resultBadge       = document.getElementById('scan-result-badge');
    const resultAlert       = document.getElementById('scan-result-alert');
    const resultIcon        = document.getElementById('scan-result-icon');
    const resultText        = document.getElementById('scan-result-text');
    const formDistribusi    = document.getElementById('form-distribusi');
    const btnKonfirmasi     = document.getElementById('btn-konfirmasi');

    let isScanning = false;

    const qrConfig = {
        fps: 15,
        qrbox: { width: 240, height: 240 },
        aspectRatio: 1.0,
        rememberLastUsedCamera: true,
    };

    function onScanSuccess(decodedText) {
        stopScanner();

        inputKode.value = decodedText.trim();
        inputKode.classList.add('is-valid');

        showScanResult('success', 'bi bi-qr-code-scan', `QR terbaca: <strong>${decodedText}</strong> — Silakan konfirmasi.`);

        if (btnKonfirmasi) btnKonfirmasi.focus();
    }


    function onScanError(err) {  }

    btnStart.addEventListener('click', function () {
        placeholder.style.display = 'none';
        resultBadge.style.display = 'none';
        inputKode.classList.remove('is-valid');

        html5QrCode.start(
            { facingMode: "environment" },
            qrConfig,
            onScanSuccess,
            onScanError
        ).then(() => {
            isScanning = true;
            btnStart.style.display = 'none';
            btnStop.style.display  = 'block';
        }).catch(err => {
            placeholder.style.display = 'flex';
            showScanResult('danger', 'bi bi-exclamation-triangle-fill', `Kamera tidak dapat diakses: <strong>${err}</strong>. Pastikan izin kamera sudah diberikan di browser.`);
        });
    });

    btnStop.addEventListener('click', stopScanner);

    function stopScanner() {
        if (isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                placeholder.style.display = 'flex';
                btnStart.style.display    = 'block';
                btnStop.style.display     = 'none';
            }).catch(err => console.error("Gagal menghentikan scanner:", err));
        }
    }

    function showScanResult(type, iconClass, message) {
        resultBadge.style.display = 'block';
        resultAlert.className = `alert alert-${type} border-0 rounded-3 mb-0 fw-semibold`;
        resultIcon.className  = `bi ${iconClass} me-2`;
        resultText.innerHTML  = message;
    }

    if (formDistribusi) {
        formDistribusi.addEventListener('submit', function () {
            if (btnKonfirmasi) {
                btnKonfirmasi.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
                btnKonfirmasi.disabled  = true;
            }
            if (isScanning) html5QrCode.stop().catch(() => {});
        });
    }
    window.addEventListener('beforeunload', function () {
        if (isScanning) html5QrCode.stop().catch(() => {});
    });
});
</script>
{{-- END: QR Code Scanner --}}
@endsection
