@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Operasional')

@section('content')
@php $role = session('pengguna.role'); @endphp

<div class="row g-4 mb-4">
    @if(in_array($role, ['kepala_sppg', 'petugas']))
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100 border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-muted fw-semibold mb-0">Penerima Manfaat</h6>
                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-1">{{ $totalPenerima ?? 0 }}</h2>
                <a href="{{ route('penerima.index') }}" class="text-decoration-none small">Lihat detail <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    @endif

    @if(in_array($role, ['kepala_sppg', 'ahli_gizi']))
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100 border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-muted fw-semibold mb-0">Menu Disetujui</h6>
                    <div class="bg-success bg-opacity-10 text-success p-2 rounded">
                        <i class="bi bi-journal-check fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-1">{{ $menuDisetujui ?? 0 }}</h2>
                <a href="{{ route('menu.index') }}" class="text-decoration-none small text-success">Kelola menu <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100 border-start border-4" style="border-left-color: #003366 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-muted fw-semibold mb-0">Total Menu Dibuat</h6>
                    <div class="p-2 rounded" style="background-color: rgba(0, 51, 102, 0.1); color: #003366;">
                        <i class="bi bi-journal-richtext fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-1">{{ $totalMenu ?? 0 }}</h2>
                <a href="{{ route('menu.index') }}" class="text-decoration-none small" style="color: #003366;">Lihat semua <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100 border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-muted fw-semibold mb-0">Menu Menunggu</h6>
                    <div class="bg-warning bg-opacity-10 text-warning p-2 rounded">
                        <i class="bi bi-hourglass-split fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-1">{{ $menuPending ?? 0 }}</h2>
                <a href="{{ route('menu.index') }}" class="text-decoration-none small text-warning">Tinjau menu <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    @endif

    @if($role === 'kepala_sppg' || ($role === 'petugas' && session('pengguna.username') === 'petugasdistribusi'))
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100 border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-muted fw-semibold mb-0">Distribusi Hari Ini</h6>
                    <div class="bg-warning bg-opacity-10 text-warning p-2 rounded">
                        <i class="bi bi-box-seam fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-1">{{ $distribusiHariIni ?? 0 }}</h2>
                <a href="{{ route('distribusi.index') }}" class="text-decoration-none small text-warning">Lihat distribusi <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    @endif

    @if($role === 'kepala_sppg' || ($role === 'petugas' && session('pengguna.username') === 'petugasgudang'))
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100 border-start border-danger border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-muted fw-semibold mb-0">Stok Kritis</h6>
                    <div class="bg-danger bg-opacity-10 text-danger p-2 rounded">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-1">{{ $stokKritis ?? 0 }}</h2>
                <a href="{{ route('logistik.index') }}" class="text-decoration-none small text-danger">Cek gudang <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row g-4 mb-4">
    {{-- Fitur 1: Rekapitulasi Distribusi Harian (UC-04) --}}
    @if($role === 'kepala_sppg' || ($role === 'petugas' && session('pengguna.username') === 'petugasdistribusi'))
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line text-primary me-2"></i>Rekapitulasi Distribusi Harian</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">{{ \Carbon\Carbon::today()->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
            </div>
            <div class="card-body">
                @php
                    $porsiTersalurkan = $totalPorsiHariIni ?? 0;
                    $totalAktif = ($totalPenerima ?? 0);
                    $belumMenerima = $totalPenerimaBelum ?? 0;
                    $persentase = $totalAktif > 0 ? round(($porsiTersalurkan / $totalAktif) * 100) : 0;
                @endphp
                <div class="row text-center mb-0">
                    <div class="col-4">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <h3 class="fw-bold text-success mb-0">{{ $porsiTersalurkan }}</h3>
                            <small class="text-muted fw-semibold">Tersalurkan</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <h3 class="fw-bold text-warning mb-0">{{ $belumMenerima }}</h3>
                            <small class="text-muted fw-semibold">Belum Terima</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <h3 class="fw-bold text-primary mb-0">{{ $totalAktif }}</h3>
                            <small class="text-muted fw-semibold">Total Penerima</small>
                        </div>
                    </div>
                </div>
                <p class="small text-muted mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>Data diperbarui secara real-time dari transaksi distribusi hari ini.</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Fitur 2: Alert Stok Bahan Kritis (UC-05) --}}
    @if($role === 'kepala_sppg' || ($role === 'petugas' && session('pengguna.username') === 'petugasgudang'))
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Alert Stok Bahan Kritis</h6>
                <a href="{{ route('logistik.index') }}" class="btn btn-sm btn-outline-danger">Kelola Gudang</a>
            </div>
            <div class="card-body p-0">
                @if(isset($stokKritisList) && $stokKritisList->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabel-stok-kritis">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Bahan Makanan</th>
                                <th class="text-center">Stok Aktual</th>
                                <th class="text-center">Batas Minimum</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stokKritisList as $stok)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold">{{ $stok->bahanMakanan->nama_bahan ?? '-' }}</div>
                                    <small class="text-muted">{{ $stok->bahanMakanan->satuan ?? '' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-danger">{{ number_format($stok->stok_aktual, 1) }}</span>
                                </td>
                                <td class="text-center text-muted">{{ number_format($stok->stok_minimum, 1) }}</td>
                                <td class="text-center">
                                    @if($stok->stok_aktual <= 0)
                                        <span class="badge bg-danger px-3 py-2">HABIS</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2">KRITIS</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-check-circle fs-1 d-block mb-3 text-success"></i>
                    <p class="mb-1 fw-semibold text-success">Semua Stok Aman</p>
                    <p class="mb-0 small">Tidak ada bahan makanan yang berada di bawah ambang batas minimum.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

@if(in_array($role, ['kepala_sppg', 'ahli_gizi']))
<div class="row g-4 mb-4">
    {{-- Fitur 3: Statistik Status Gizi — Chart komposisi (UC-03) --}}
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart-fill text-success me-2"></i>Statistik Komposisi Gizi</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                @if(isset($statistikGizi) && ($statistikGizi['protein'] + $statistikGizi['karbohidrat'] + $statistikGizi['lemak']) > 0)
                <div style="position: relative; width: 100%; max-width: 280px;">
                    <canvas id="chartGizi"></canvas>
                </div>
                <div class="row w-100 mt-4 text-center g-2">
                    <div class="col-4">
                        <div class="bg-light rounded-3 p-2">
                            <div class="fw-bold text-primary">{{ $statistikGizi['protein'] }}g</div>
                            <small class="text-muted">Protein</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded-3 p-2">
                            <div class="fw-bold text-warning">{{ $statistikGizi['karbohidrat'] }}g</div>
                            <small class="text-muted">Karbo</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded-3 p-2">
                            <div class="fw-bold text-danger">{{ $statistikGizi['lemak'] }}g</div>
                            <small class="text-muted">Lemak</small>
                        </div>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0 text-center"><i class="bi bi-info-circle me-1"></i>Rata-rata komposisi dari <strong>{{ $menuDisetujui ?? 0 }}</strong> menu yang disetujui. Kalori rata-rata: <strong>{{ $statistikGizi['kalori'] }} kCal</strong></p>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-bar-chart fs-1 d-block mb-3"></i>
                    <p class="mb-0">Belum ada data gizi dari menu yang disetujui.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Menu Gizi Terbaru (sudah ada sebelumnya) --}}
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-journal-text text-success me-2"></i>Menu Gizi Terbaru</h6>
                <a href="{{ route('menu.index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if(isset($menuTerbaru) && $menuTerbaru->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="tabel-menu-terbaru">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nama Menu</th>
                                <th>Kalori</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menuTerbaru as $m)
                            <tr>
                                <td class="ps-3 fw-semibold">{{ $m->nama_menu }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $m->total_kalori }} kCal</span></td>
                                <td class="text-muted small">{{ \Carbon\Carbon::parse($m->tanggal_berlaku)->format('d M Y') }}</td>
                                <td>
                                    @if($m->status === 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($m->status === 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                    <p class="mb-0">Belum ada menu gizi yang dibuat.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="row g-4">
    @if($role === 'kepala_sppg' || ($role === 'petugas' && session('pengguna.username') !== 'petugasgudang'))
    {{-- Fitur 4: Log Aktivitas Terbaru — 5 transaksi terakhir --}}
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>Log Aktivitas Terbaru</h6>
                <a href="{{ route('distribusi.riwayat') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if(isset($logAktivitas) && $logAktivitas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="tabel-log-aktivitas">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Penerima</th>
                                <th>Menu / Paket</th>
                                <th>Petugas</th>
                                <th>Waktu</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logAktivitas as $log)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold">{{ $log->penerima->nama ?? '-' }}</div>
                                    <small class="text-muted font-monospace">{{ $log->penerima->kode_penerima ?? '' }}</small>
                                </td>
                                <td>{{ $log->jadwal->menuGizi->nama_menu ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border"><i class="bi bi-person me-1"></i>{{ $log->petugas->nama ?? '-' }}</span>
                                </td>
                                <td class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $log->waktu_distribusi->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                </td>
                                <td class="text-center">
                                    @if($log->status === 'terdistribusi')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i>Tersalurkan
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                                            <i class="bi bi-x-circle-fill me-1"></i>Dibatalkan
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="mb-0">Belum ada data aktivitas distribusi.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
@if(in_array($role, ['kepala_sppg', 'ahli_gizi']) && isset($statistikGizi))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('chartGizi');
        if (!ctx) return;

        const dataGizi = {
            protein: {{ $statistikGizi['protein'] ?? 0 }},
            karbohidrat: {{ $statistikGizi['karbohidrat'] ?? 0 }},
            lemak: {{ $statistikGizi['lemak'] ?? 0 }}
        };

    
        if (dataGizi.protein + dataGizi.karbohidrat + dataGizi.lemak <= 0) return;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Protein (g)', 'Karbohidrat (g)', 'Lemak (g)'],
                datasets: [{
                    data: [dataGizi.protein, dataGizi.karbohidrat, dataGizi.lemak],
                    backgroundColor: ['#0d6efd', '#ffc107', '#dc3545'],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 16,
                            usePointStyle: true,
                            pointStyleWidth: 12,
                            font: { size: 12, weight: '500' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const persen = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                return ` ${ctx.label}: ${ctx.parsed}g (${persen}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
