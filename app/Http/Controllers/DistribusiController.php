<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distribusi;
use App\Models\PenerimaManfaat;
use App\Models\JadwalDistribusi;
use App\Models\StokBahan;

class DistribusiController extends Controller
{
    private function cekAkses(Request $request)
    {
        if (!$request->session()->has('pengguna')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $role = $request->session()->get('pengguna.role');
        $username = $request->session()->get('pengguna.username');

        if ($role !== 'kepala_sppg' && !str_starts_with($role, 'petugas')) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Petugas Operasional atau Super Admin (Kepala SPPG).');
        }

        if ($role === 'petugas' && $username !== 'petugasdistribusi') {
            abort(403, 'Akses ditolak. Hanya Petugas Distribusi yang memiliki wewenang untuk mengelola menu ini.');
        }
        return null;
    }

    public function index(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $distribusiHariIni = Distribusi::with(['penerima', 'jadwal.menuGizi', 'petugas'])
            ->whereDate('waktu_distribusi', today())
            ->orderByDesc('waktu_distribusi')
            ->paginate(20);

        $jadwalHariIni = JadwalDistribusi::with('menuGizi')
            ->where('tanggal_distribusi', today())
            ->first();

        return view('distribusi.index', compact('distribusiHariIni', 'jadwalHariIni'));
    }

    public function scan(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $jadwalHariIni = JadwalDistribusi::with('menuGizi')
            ->where('tanggal_distribusi', today())
            ->first();

        return view('distribusi.scan', compact('jadwalHariIni'));
    }

    public function konfirmasi(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $request->validate([
            'kode_penerima'        => 'required|string',
            'jadwal_distribusi_id' => 'required|exists:jadwal_distribusi,id',
        ]);

        $penerima = PenerimaManfaat::where('kode_penerima', $request->kode_penerima)->first();

        if (!$penerima) {
            return back()->with('error', 'QR Code tidak valid! Data penerima manfaat tidak ditemukan.');
        }

        if (!$penerima->is_active) {
            return back()->with('error', 'Penerima manfaat ini sudah dinonaktifkan.');
        }

        $sudahMenerima = Distribusi::where('penerima_manfaat_id', $penerima->id)
            ->where('jadwal_distribusi_id', $request->jadwal_distribusi_id)
            ->whereDate('waktu_distribusi', today())
            ->where('status', 'terdistribusi')
            ->exists();

        if ($sudahMenerima) {
            return back()->with('error', $penerima->nama . ' sudah menerima porsi makanan untuk hari ini.');
        }

        $jadwal   = JadwalDistribusi::with('menuGizi')->find($request->jadwal_distribusi_id);
        $pengguna = $request->session()->get('pengguna');

        Distribusi::create([
            'penerima_manfaat_id'  => $penerima->id,
            'jadwal_distribusi_id' => $jadwal->id,
            'petugas_id'           => $pengguna['id'],
            'status'               => 'terdistribusi',
            'keterangan'           => null,
            'waktu_distribusi'     => now(),
        ]);

        $this->potongStokOtomatis($jadwal);

        return back()->with('success', 'Distribusi berhasil! ' . $penerima->nama . ' telah menerima porsi gizi hari ini.');
    }

    private function potongStokOtomatis(JadwalDistribusi $jadwal): void
    {
        $stokList = StokBahan::where('menu_gizi_id', $jadwal->menu_gizi_id)->get();

        foreach ($stokList as $stok) {
            $stok->update([
                'stok_aktual'     => max(0, $stok->stok_aktual - $stok->kebutuhan_per_porsi),
                'terakhir_diubah' => now(),
            ]);
        }
    }

    public function riwayat(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $riwayat = Distribusi::with(['penerima', 'jadwal.menuGizi', 'petugas'])
            ->when($request->tanggal, fn($q) => $q->whereDate('waktu_distribusi', $request->tanggal))
            ->when($request->search, fn($q) => $q->whereHas('penerima', fn($pq) =>
                $pq->where('nama', 'like', '%' . $request->search . '%')
                   ->orWhere('kode_penerima', 'like', '%' . $request->search . '%')
            ))
            ->orderByDesc('waktu_distribusi')
            ->paginate(20);

        return view('distribusi.riwayat', compact('riwayat'));
    }

    public function batalkan(Request $request, Distribusi $distribusi)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        if ($distribusi->status !== 'terdistribusi') {
            return back()->with('error', 'Data ini sudah berstatus "' . $distribusi->status . '" dan tidak bisa dibatalkan lagi.');
        }

        // Kembalikan stok bahan otomatis
        $this->kembalikanStok($distribusi->jadwal);

        $distribusi->update([
            'status'     => 'dibatalkan',
            'keterangan' => 'Dibatalkan secara manual oleh ' . $request->session()->get('pengguna.nama') . ' pada ' . now()->format('d/m/Y H:i'),
        ]);

        return back()->with('success', 'Distribusi atas nama ' . ($distribusi->penerima->nama ?? '-') . ' berhasil dibatalkan.');
    }

    private function kembalikanStok(JadwalDistribusi $jadwal): void
    {
        $stokList = StokBahan::where('menu_gizi_id', $jadwal->menu_gizi_id)->get();

        foreach ($stokList as $stok) {
            $stok->update([
                'stok_aktual'     => $stok->stok_aktual + $stok->kebutuhan_per_porsi,
                'terakhir_diubah' => now(),
            ]);
        }
    }
}
