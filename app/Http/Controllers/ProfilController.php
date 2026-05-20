<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class ProfilController extends Controller
{
    private function cekAkses(Request $request)
    {
        if (!$request->session()->has('pengguna')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        return null;
    }

    public function index(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $penggunaSession = $request->session()->get('pengguna');
        $profil = Pengguna::with(['institusi', 'peran'])->findOrFail($penggunaSession['id']);

        return view('profil.index', compact('profil'));
    }

    public function update(Request $request)
    {
        $redirect = $this->cekAkses($request);
        if ($redirect) return $redirect;

        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'foto_profil.required' => 'Silakan pilih file foto terlebih dahulu.',
            'foto_profil.image'    => 'File harus berupa gambar.',
            'foto_profil.mimes'    => 'Format gambar harus jpeg, png, atau jpg.',
            'foto_profil.max'      => 'Ukuran foto maksimal 2 MB.',
        ]);

        $penggunaSession = $request->session()->get('pengguna');
        $profil = Pengguna::findOrFail($penggunaSession['id']);

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = 'profil_' . $profil->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profil'), $filename);
            
            if ($profil->foto_profil && file_exists(public_path('uploads/profil/' . $profil->foto_profil))) {
                unlink(public_path('uploads/profil/' . $profil->foto_profil));
            }

            $profil->update(['foto_profil' => $filename]);
            
            $penggunaSession['foto_profil'] = $filename;
            $request->session()->put('pengguna', $penggunaSession);
        }

        return redirect()->route('profil.index')->with('success', 'Foto profil berhasil diperbarui.');
    }
}
