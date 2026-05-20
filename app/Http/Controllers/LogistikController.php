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
            ->when($request->search, fn($q) => $q->whereHas('bahanMakanan', fn($bq) =>
                $bq->where('nama_bahan', 'like', '%' . $request->search . '%')
            ))
            ->orderByRaw('stok_aktual <= stok_minimum DESC')
            ->orderBy('stok_aktual', 'asc')
            ->paginate(20);

        $jumlahKritis = StokBahan::whereRaw('stok_aktual <= stok_minimum')->count();

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
            'stok_minimum'       => 'nullable|numeric|min:0',
            'keterangan'         => 'nullable|string|max:255',
        ]);

        $stok = StokBahan::firstOrCreate(
            ['bahan_makanan_id' => $request->bahan_makanan_id],
            ['stok_aktual' => 0, 'stok_minimum' => $request->stok_minimum ?? 0]
        );

        $stok->update([
            'stok_aktual'     => $stok->stok_aktual + $request->jumlah_ditambahkan,
            'stok_minimum'    => $request->stok_minimum ?? $stok->stok_minimum,
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
        ]);

        BahanMakanan::create($request->only([
            'nama_bahan', 'satuan'
        ]));

        return redirect()->route('logistik.index')->with('success', 'Bahan makanan baru berhasil ditambahkan ke master data.');
    }
}
