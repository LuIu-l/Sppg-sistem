<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\PenerimaManfaat;
use App\Models\Institusi;

class PenerimaManfaatController extends Controller
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

        if ($role === 'petugas' && $username === 'petugasgudang') {
            abort(403, 'Akses ditolak. Petugas Gudang tidak memiliki wewenang untuk mengakses data penerima manfaat.');
        }
        return null;
    }

    public function index(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $penerima = PenerimaManfaat::with('institusi')
            ->when($request->search, fn($q) => $q->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('nik', 'like', '%' . $request->search . '%')
                ->orWhere('nisn', 'like', '%' . $request->search . '%')
                ->orWhere('kode_penerima', 'like', '%' . $request->search . '%'))
            ->orderBy('nama')
            ->paginate(15);

        return view('penerima.index', compact('penerima'));
    }

    public function create(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        if ($request->session()->get('pengguna.role') !== 'kepala_sppg') {
            abort(403, 'Hanya Kepala SPPG yang dapat menambah penerima manfaat baru.');
        }

        $institusiList = Institusi::orderBy('nama_institusi')->get();
        return view('penerima.create', compact('institusiList'));
    }

    public function store(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        if ($request->session()->get('pengguna.role') !== 'kepala_sppg') {
            abort(403, 'Hanya Kepala SPPG yang dapat menambah penerima manfaat baru.');
        }

        $request->validate([
            'nama'          => 'required|string|max:255',
            'nik'           => 'nullable|digits:16|unique:penerima_manfaat,nik',
            'nisn'          => 'nullable|digits:10|unique:penerima_manfaat,nisn',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'required|string',
            'institusi_id'  => 'required|exists:institusi,id',
            'pin'           => 'required|string|min:4|max:6',
        ], [
            'nik.digits'  => 'NIK harus tepat 16 digit angka.',
            'nisn.digits' => 'NISN harus tepat 10 digit angka.',
            'nik.unique'  => 'NIK ini sudah terdaftar dalam sistem.',
            'nisn.unique' => 'NISN ini sudah terdaftar dalam sistem.',
        ]);

        PenerimaManfaat::create([
            'kode_penerima' => 'PM-' . strtoupper(Str::random(8)),
            'nama'          => $request->nama,
            'nik'           => $request->nik,
            'nisn'          => $request->nisn,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat'        => $request->alamat,
            'institusi_id'  => $request->institusi_id,
            'pin'           => Hash::make($request->pin),
            'is_active'     => true,
        ]);

        return redirect()->route('penerima.index')->with('success', 'Data penerima manfaat berhasil didaftarkan.');
    }

    public function edit(Request $request, PenerimaManfaat $penerima)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        if ($request->session()->get('pengguna.role') !== 'kepala_sppg') {
            abort(403, 'Hanya Kepala SPPG yang dapat mengubah data penerima manfaat.');
        }

        $institusiList = Institusi::orderBy('nama_institusi')->get();
        return view('penerima.edit', compact('penerima', 'institusiList'));
    }

    public function update(Request $request, PenerimaManfaat $penerima)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        if ($request->session()->get('pengguna.role') !== 'kepala_sppg') {
            abort(403, 'Hanya Kepala SPPG yang dapat mengubah data penerima manfaat.');
        }

        $request->validate([
            'nama'          => 'required|string|max:255',
            'nik'           => 'nullable|digits:16|unique:penerima_manfaat,nik,' . $penerima->id,
            'nisn'          => 'nullable|digits:10|unique:penerima_manfaat,nisn,' . $penerima->id,
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'required|string',
            'institusi_id'  => 'required|exists:institusi,id',
            'is_active'     => 'boolean',
        ]);

        $data = $request->only(['nama', 'nik', 'nisn', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'institusi_id']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->filled('pin')) {
            $request->validate(['pin' => 'string|min:4|max:6']);
            $data['pin'] = Hash::make($request->pin);
        }

        $penerima->update($data);
        return redirect()->route('penerima.index')->with('success', 'Data penerima manfaat berhasil diperbarui.');
    }
}
