<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanMakanan;
use App\Models\StokBahan;

class LogistikController extends Controller
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

        if ($role === 'petugas' && $username !== 'petugasgudang') {
            abort(403, 'Akses ditolak. Hanya Petugas Gudang yang memiliki wewenang untuk mengelola logistik.');
        }
        return null;
    }

    public function index(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $stokList = StokBahan::with('bahanMakanan')
            ->select('stok_bahan.*', 'bahan_makanan.stok_minimum')
            ->join('bahan_makanan', 'bahan_makanan.id', '=', 'stok_bahan.bahan_makanan_id')
            ->when($request->search, fn($q) => $q->where('bahan_makanan.nama_bahan', 'like', '%' . $request->search . '%'))
            ->orderByRaw('stok_bahan.stok_aktual <= bahan_makanan.stok_minimum DESC')
            ->orderBy('stok_bahan.stok_aktual', 'asc')
            ->paginate(20);

        $jumlahKritis = StokBahan::join('bahan_makanan', 'bahan_makanan.id', '=', 'stok_bahan.bahan_makanan_id')
            ->whereRaw('stok_bahan.stok_aktual <= bahan_makanan.stok_minimum')
            ->count();

        return view('logistik.index', compact('stokList', 'jumlahKritis'));
    }

    public function form(Request $request, ?StokBahan $stok = null)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $bahanMakananList = BahanMakanan::orderBy('nama_bahan')->get();
        return view('logistik.form', compact('stok', 'bahanMakananList'));
    }

    public function tambahStok(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $request->validate([
            'bahan_makanan_id'   => 'required|exists:bahan_makanan,id',
            'jumlah_ditambahkan' => 'required|numeric|min:0.01',
            'keterangan'         => 'nullable|string|max:255',
        ]);

        $stok = StokBahan::firstOrCreate(
            ['bahan_makanan_id' => $request->bahan_makanan_id],
            ['stok_aktual' => 0]
        );

        $stok->update([
            'stok_aktual'     => $stok->stok_aktual + $request->jumlah_ditambahkan,
            'terakhir_diubah' => now(),
        ]);

        return redirect()->route('logistik.index')->with('success', 'Stok bahan makanan berhasil ditambahkan.');
    }

    public function masterBahan(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $bahanMakanan = BahanMakanan::withCount('stokBahan')->paginate(20);
        return view('logistik.master_bahan', compact('bahanMakanan'));
    }

    public function storeBahan(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $request->validate([
            'nama_bahan'             => 'required|string|max:255|unique:bahan_makanan,nama_bahan',
            'satuan'                 => 'required|string|max:50',
            'stok_minimum'           => 'required|numeric|min:0',
        ]);

        BahanMakanan::create($request->only([
            'nama_bahan', 'satuan', 'stok_minimum'
        ]));

        return redirect()->route('logistik.index')->with('success', 'Bahan makanan baru berhasil ditambahkan ke master data.');
    }
}
