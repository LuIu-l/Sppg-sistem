<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Institusi;

class AkunController extends Controller
{
    private function cekAksesAdmin(Request $request)
    {
        $pengguna = $request->session()->get('pengguna');
        if (!$pengguna) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        if ($pengguna['role'] !== 'kepala_sppg') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Kepala SPPG yang berwenang.');
        }
        return null;
    }

    public function index(Request $request)
    {
        $redirect = $this->cekAksesAdmin($request);
        if ($redirect) return $redirect;

        $pengguna = Pengguna::with(['peran', 'institusi'])
            ->when($request->search, fn($q) => $q->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('username', 'like', '%' . $request->search . '%'))
            ->orderBy('nama')
            ->paginate(15);

        return view('akun.index', compact('pengguna'));
    }

    public function form(Request $request, ?Pengguna $akun = null)
    {
        $redirect = $this->cekAksesAdmin($request);
        if ($redirect) return $redirect;

        $peranList     = Peran::orderBy('nama_peran')->get();
        $institusiList = Institusi::orderBy('nama_institusi')->get();
        return view('akun.form', compact('akun', 'peranList', 'institusiList'));
    }

    public function store(Request $request)
    {
        $redirect = $this->cekAksesAdmin($request);
        if ($redirect) return $redirect;

        $request->validate([
            'nama'         => 'required|string|max:255',
            'username'     => 'required|string|max:100|unique:pengguna,username',
            'email'        => 'required|email|unique:pengguna,email',
            'password'     => 'required|string|min:8|confirmed',
            'peran_id'     => 'required|exists:peran,id',
            'institusi_id' => 'nullable|exists:institusi,id',
        ]);

        Pengguna::create([
            'nama'         => $request->nama,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'peran_id'     => $request->peran_id,
            'institusi_id' => $request->institusi_id,
            'is_active'    => true,
        ]);

        return redirect()->route('akun.index')->with('success', 'Akun pengguna baru berhasil ditambahkan.');
    }

    public function update(Request $request, Pengguna $akun)
    {
        $redirect = $this->cekAksesAdmin($request);
        if ($redirect) return $redirect;

        $request->validate([
            'nama'         => 'required|string|max:255',
            'username'     => 'required|string|max:100|unique:pengguna,username,' . $akun->id,
            'email'        => 'required|email|unique:pengguna,email,' . $akun->id,
            'peran_id'     => 'required|exists:peran,id',
            'institusi_id' => 'nullable|exists:institusi,id',
            'is_active'    => 'boolean',
        ]);

        $data = $request->only(['nama', 'username', 'email', 'peran_id', 'institusi_id']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $akun->update($data);
        return redirect()->route('akun.index')->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function toggleAktif(Request $request, Pengguna $akun)
    {
        $redirect = $this->cekAksesAdmin($request);
        if ($redirect) return $redirect;

        $akun->update(['is_active' => !$akun->is_active]);
        $status = $akun->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('akun.index')->with('success', "Akun '{$akun->nama}' berhasil {$status}.");
    }
}
