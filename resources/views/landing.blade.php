<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi SPPG - Layanan Gizi Masyarakat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<style>html { scroll-behavior: smooth; }</style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-shield-check text-primary me-2"></i>SPPG Terpadu
            </a>
            <div>
                @if(session()->has('penerima'))
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right me-1"></i> Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-primary px-3"><i class="bi bi-box-arrow-in-right me-1"></i> Portal Login</a>
                @endif
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @isset($penerima)
        <div class="row justify-content-center pt-3 mb-5">
            <div class="col-12 text-center mb-4">
                <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-3 py-2 rounded-pill mb-2 d-inline-block">Kartu Digital Anda</span>
                <h2 class="fw-bold text-dark mb-1">Selamat Datang, {{ $penerima->nama }}!</h2>
                <p class="text-muted">Tunjukkan QR Code ini saat pengambilan porsi di pusat distribusi.</p>
            </div>
            <div class="col-lg-10 col-md-12">
                <div class="row">
                
                    <div class="col-md-6 mb-4">
                        <div class="card shadow border-0 rounded-4 overflow-hidden h-100">
                            <div class="bg-primary text-white p-4 text-center">
                                <h5 class="fw-bold mb-1">KARTU DIGITAL PENERIMA</h5>
                                <p class="mb-0 small opacity-75">Sistem Pemenuhan Gizi</p>
                            </div>
                            <div class="card-body p-4 text-center">
                                <h3 class="fw-bold text-dark mb-1">{{ $penerima->nama }}</h3>
                                <p class="text-muted mb-4">{{ $penerima->institusi->nama_institusi ?? 'Institusi tidak ditemukan' }}</p>

                                <div class="bg-light rounded-4 p-4 mb-4 d-inline-block border">
                                    <div id="qr-canvas" class="d-flex justify-content-center mb-2"></div>
                                    <span class="badge bg-secondary px-3 py-2 fs-6 mt-2 font-monospace">{{ $penerima->kode_penerima }}</span>
                                </div>
                                
                                <p class="small text-muted mb-0">Tunjukkan QR Code ini kepada petugas distribusi untuk dipindai.</p>
                            </div>
                            <div class="card-footer bg-white p-3 border-top-0 mt-auto">
                                <div class="row text-center small text-muted">
                                    <div class="col-6 border-end">
                                        <span class="d-block fw-bold text-dark">NIK</span>
                                        {{ $penerima->nik ?? '-' }}
                                    </div>
                                    <div class="col-6">
                                        <span class="d-block fw-bold text-dark">NISN</span>
                                        {{ $penerima->nisn ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="d-flex flex-column h-100">
                            @if($sudahMenerimaHariIni ?? false)
                                <div class="alert alert-success shadow-sm mb-4 border-0 rounded-4">
                                    <i class="bi bi-check-circle-fill me-2"></i> Anda <strong>sudah</strong> menerima porsi gizi hari ini.
                                </div>
                            @else
                                <div class="alert alert-warning shadow-sm mb-4 border-0 rounded-4">
                                    <i class="bi bi-clock-fill me-2"></i> Anda <strong>belum</strong> menerima porsi gizi hari ini. Segera ke pusat distribusi.
                                </div>
                            @endif

                            <div class="card shadow border-0 rounded-4 flex-grow-1 overflow-hidden">
                                <div class="card-header bg-white p-4 border-bottom-0 fw-bold text-dark">
                                    <i class="bi bi-clock-history text-primary me-2"></i> Riwayat 5 Distribusi Terakhir
                                </div>
                                @if(isset($riwayatDistribusi) && $riwayatDistribusi->count() > 0)
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        @foreach($riwayatDistribusi as $r)
                                        <li class="list-group-item px-4 py-3 border-light">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold text-dark">{{ $r->jadwal->menuGizi->nama_menu ?? 'Menu' }}</h6>
                                                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $r->waktu_distribusi->locale('id')->isoFormat('dddd, D MMMM Y, HH:mm') }}</small>
                                                </div>
                                                <div class="text-success fs-5"><i class="bi bi-check-circle-fill"></i></div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @else
                                <div class="card-body d-flex flex-column align-items-center justify-content-center text-muted p-5">
                                    <i class="bi bi-journal-x fs-1 mb-3"></i>
                                    <p class="mb-0 text-center">Belum ada riwayat distribusi.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            new QRCode(document.getElementById("qr-canvas"), {
                text: "{{ $penerima->kode_penerima }}",
                width: 200,
                height: 200,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        </script>

        <hr class="my-4 text-muted opacity-25">
        @endisset

        {{-- HALAMAN PUBLIK (SELALU TAMPIL DI BAWAH) --}}
        <div class="row align-items-center justify-content-center py-5">
            <div class="col-md-6 text-center text-md-start mb-5 mb-md-0">
                <h1 class="display-5 fw-bold text-dark mb-3">Layanan Gizi Masyarakat Terpadu</h1>
                <p class="lead text-muted mb-4">Sistem Informasi Manajemen untuk distribusi program pemenuhan gizi yang transparan dan tepat sasaran.</p>
            </div>
            
            <div class="col-md-5 offset-md-1">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="bi bi-calendar-event text-primary me-2"></i> Jadwal Menu Berikutnya</h5>
                        
                        @isset($jadwalBerikutnya)
                        <div class="mb-3">
                            <h4 class="text-success fw-bold">{{ $jadwalBerikutnya->menuGizi->nama_menu ?? '-' }}</h4>
                            <p class="mb-2 text-muted">{{ $jadwalBerikutnya->menuGizi->catatan ?? 'Menu sehat gizi seimbang' }}</p>
                        </div>
                        <ul class="list-unstyled mb-0 bg-light p-3 rounded">
                            <li class="mb-2"><i class="bi bi-calendar3 text-primary me-2"></i> {{ \Carbon\Carbon::parse($jadwalBerikutnya->tanggal_distribusi)->locale('id')->isoFormat('dddd, D MMMM Y') }}</li>
                            @if($jadwalBerikutnya->waktu_mulai)
                                <li><i class="bi bi-clock text-primary me-2"></i> Pukul {{ $jadwalBerikutnya->waktu_mulai }} WIB — {{ $jadwalBerikutnya->lokasi ?? 'Pusat Distribusi' }}</li>
                            @endif
                        </ul>
                        @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                            <p class="mb-0">Belum ada jadwal distribusi yang direncanakan.</p>
                        </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>

        <div class="py-5">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2 rounded-pill mb-2 d-inline-block">Jaringan Program</span>
                <h2 class="fw-bold text-dark mb-2">Sekolah Mitra SPPG</h2>
                <p class="text-muted">Institusi pendidikan yang telah bergabung dalam program pemenuhan gizi terpadu.</p>
            </div>
            @if(isset($institusiList) && $institusiList->count() > 0)
            <div class="row g-4 justify-content-center">
                @foreach($institusiList as $inst)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100 text-center p-2">
                        <div class="card-body p-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                                <i class="bi bi-building text-primary fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">{{ $inst->nama_institusi }}</h5>
                            <p class="text-muted small mb-3">{{ $inst->alamat ?? 'Margahayu, Bandung' }}</p>
                            <div class="d-flex justify-content-center gap-3">
                                <div class="text-center">
                                    <div class="fw-bold text-primary fs-5">{{ $inst->penerima_manfaat_count }}</div>
                                    <small class="text-muted">Penerima Aktif</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-success bg-opacity-10 text-success small fw-semibold rounded-bottom-4 py-2 border-0">
                            <i class="bi bi-check-circle-fill me-1"></i> Terdaftar &amp; Aktif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-building fs-1 d-block mb-2"></i>
                <p>Belum ada institusi yang terdaftar.</p>
            </div>
            @endif
        </div>

        <hr class="my-2 text-muted opacity-25">
        <div class="py-5">
            <div class="text-center mb-5">
                <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-3 py-2 rounded-pill mb-2 d-inline-block">Tim Operasional</span>
                <h2 class="fw-bold text-dark mb-2">Tim Petugas SPPG</h2>
                <p class="text-muted">Petugas kami yang berdedikasi memastikan program gizi berjalan lancar setiap harinya.</p>
            </div>
            @if(isset($petugas) && $petugas->count() > 0)
            <div class="row g-4 justify-content-center">
                @foreach($petugas as $p)
                @php
                    $icons = ['bi-star-fill', 'bi-clipboard2-pulse', 'bi-person-workspace', 'bi-boxes', 'bi-qr-code-scan'];
                    $colors = ['danger', 'info', 'primary', 'warning', 'success'];
                    $descriptions = [
                        'kepala sppg'        => 'Memimpin dan mengawasi seluruh operasional pelayanan pemenuhan gizi.',
                        'ahligizi'           => 'Menyusun menu sehat dan memastikan standar gizi setiap porsi makanan.',
                        'petugasdaftar'      => 'Bertanggung jawab atas pendaftaran & verifikasi data penerima manfaat.',
                        'petugasgudang'      => 'Mengelola stok & logistik bahan makanan di gudang distribusi.',
                        'petugasdistribusi'  => 'Melaksanakan distribusi porsi gizi langsung ke penerima manfaat.',
                    ];
                    $desc = $descriptions[$p->username] ?? 'Mendukung kelancaran operasional program gizi SPPG.';
                    $i = $loop->index % 5;
                @endphp
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100 text-center p-2">
                        <div class="card-body p-4">
                            <div class="bg-{{ $colors[$i] }} bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                                <i class="bi {{ $icons[$i] }} text-{{ $colors[$i] }} fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">{{ $p->nama }}</h5>
                            <span class="badge bg-{{ $colors[$i] }} bg-opacity-10 text-{{ $colors[$i] }} mb-3">{{ $p->peran->nama_peran ?? 'Petugas' }}</span>
                            <p class="text-muted small mb-0">{{ $desc }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-people fs-1 d-block mb-2"></i>
                <p>Data tim petugas belum tersedia.</p>
            </div>
            @endif
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
