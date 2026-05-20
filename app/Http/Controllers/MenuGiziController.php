<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuGizi;
use App\Models\BahanMakanan;

class MenuGiziController extends Controller
{
    private function cekAkses(Request $request)
    {
        if (!$request->session()->has('pengguna')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $role = $request->session()->get('pengguna.role');
        if (!in_array($role, ['kepala_sppg', 'ahli_gizi'])) {
            abort(403, 'Akses ditolak. Halaman ini khusus Kepala SPPG dan Ahli Gizi.');
        }
        return null;
    }
    
    private function cekAhliGizi(Request $request)
    {
        $role = $request->session()->get('pengguna.role');
        if ($role !== 'ahli_gizi' && $role !== 'kepala_sppg') {
            abort(403, 'Hanya Ahli Gizi atau Super Admin (Kepala SPPG) yang dapat mengelola data menu gizi.');
        }
    }

    public function index(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $menu = MenuGizi::with(['pembuat', 'penyetuju'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('tanggal_berlaku')
            ->paginate(15);

        return view('menu.index', compact('menu'));
    }

    public function create(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;
        $this->cekAhliGizi($request);

        $bahanMakanan = BahanMakanan::orderBy('nama_bahan')->get();
        return view('menu.create', compact('bahanMakanan'));
    }

    public function store(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;
        $this->cekAhliGizi($request);

        $request->validate([
            'nama_menu'         => 'required|string|max:255',
            'tanggal_berlaku'   => 'required|date',
            'total_kalori'      => 'required|numeric|min:0',
            'total_protein'     => 'required|numeric|min:0',
            'total_karbohidrat' => 'required|numeric|min:0',
            'total_lemak'       => 'required|numeric|min:0',
            'catatan'           => 'nullable|string',
        ]);

        $pengguna = $request->session()->get('pengguna');

        MenuGizi::create([
            'nama_menu'         => $request->nama_menu,
            'tanggal_berlaku'   => $request->tanggal_berlaku,
            'total_kalori'      => $request->total_kalori,
            'total_protein'     => $request->total_protein,
            'total_karbohidrat' => $request->total_karbohidrat,
            'total_lemak'       => $request->total_lemak,
            'catatan'           => $request->catatan,
            'status'            => 'menunggu',
            'dibuat_oleh'       => $pengguna['id'],
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu gizi berhasil dibuat dan menunggu persetujuan.');
    }

    public function setujui(Request $request, MenuGizi $menu)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $pengguna = $request->session()->get('pengguna');

        if ($pengguna['role'] !== 'kepala_sppg') {
            return back()->with('error', 'Hanya Kepala SPPG yang berwenang menyetujui menu gizi.');
        }

        $menu->update([
            'status'         => 'disetujui',
            'disetujui_oleh' => $pengguna['id'],
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu gizi berhasil disetujui.');
    }

    public function tolak(Request $request, MenuGizi $menu)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $request->validate(['catatan_penolakan' => 'required|string']);

        $pengguna = $request->session()->get('pengguna');

        if ($pengguna['role'] !== 'kepala_sppg') {
            return back()->with('error', 'Hanya Kepala SPPG yang berwenang menolak menu gizi.');
        }

        $menu->update([
            'status'            => 'ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
            'disetujui_oleh'    => $pengguna['id'],
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu gizi telah ditolak dengan catatan.');
    }

    public function edit(Request $request, MenuGizi $menu)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;
        $this->cekAhliGizi($request);

        $bahanMakanan = BahanMakanan::orderBy('nama_bahan')->get();
        return view('menu.edit', compact('menu', 'bahanMakanan'));
    }

    public function update(Request $request, MenuGizi $menu)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;
        $this->cekAhliGizi($request);

        $request->validate([
            'nama_menu'         => 'required|string|max:255',
            'tanggal_berlaku'   => 'required|date',
            'total_kalori'      => 'required|numeric|min:0',
            'total_protein'     => 'required|numeric|min:0',
            'total_karbohidrat' => 'required|numeric|min:0',
            'total_lemak'       => 'required|numeric|min:0',
        ]);

        $menu->update($request->only([
            'nama_menu', 'tanggal_berlaku', 'total_kalori',
            'total_protein', 'total_karbohidrat', 'total_lemak', 'catatan',
        ]));

        return redirect()->route('menu.index')->with('success', 'Menu gizi berhasil diperbarui.');
    }
}
