<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use App\Models\PenerimaManfaat;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (session()->has('pengguna')) {
            return redirect()->route('dashboard');
        }
        if (session()->has('penerima')) {
            return redirect()->route('landing');
        }

        return view('auth.login_app');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identitas' => 'required|string',
            'password'  => 'required|string',
        ], [
            'identitas.required' => 'Kolom identitas (Username / NIK / NISN) wajib diisi.',
            'password.required'  => 'Kolom password / PIN wajib diisi.',
        ]);

        $identitas = trim($request->identitas);
        $password  = $request->password;

        if (preg_match('/^\d{10}$/', $identitas)) {
            return $this->loginSebagaiPenerima($request, 'nisn', $identitas, $password);
        }

        if (preg_match('/^\d{16}$/', $identitas)) {
            return $this->loginSebagaiPenerima($request, 'nik', $identitas, $password);
        }

        return $this->loginSebagaiStaf($request, $identitas, $password);
    }

    private function loginSebagaiPenerima(Request $request, string $kolom, string $nilai, string $pin)
    {
        $penerima = PenerimaManfaat::where($kolom, $nilai)->first();

        if (!$penerima) {
            return back()->withInput()->withErrors([
                'identitas' => "Data penerima manfaat dengan {$kolom} tersebut tidak ditemukan.",
            ]);
        }

        if (!Hash::check($pin, $penerima->pin)) {
            return back()->withInput()->withErrors([
                'password' => 'PIN yang Anda masukkan salah.',
            ]);
        }

        if (!$penerima->is_active) {
            return back()->withInput()->withErrors([
                'identitas' => 'Akun penerima manfaat Anda telah dinonaktifkan. Hubungi petugas.',
            ]);
        }

        $request->session()->regenerate();

        $request->session()->put('penerima', [
            'id'            => $penerima->id,
            'nama'          => $penerima->nama,
            'nik'           => $penerima->nik,
            'nisn'          => $penerima->nisn,
            'kode_penerima' => $penerima->kode_penerima,
            'institusi_id'  => $penerima->institusi_id,
        ]);

        return redirect()->route('landing');
    }

    private function loginSebagaiStaf(Request $request, string $identitas, string $password)
    {
        $pengguna = Pengguna::where('username', $identitas)
                            ->orWhere('email', $identitas)
                            ->first();

        if (!$pengguna) {
            return back()->withInput()->withErrors([
                'identitas' => 'Username atau email tidak ditemukan dalam sistem.',
            ]);
        }

        if (!Hash::check($password, $pengguna->password)) {
            return back()->withInput()->withErrors([
                'password' => 'Password yang Anda masukkan salah.',
            ]);
        }

        if (!$pengguna->is_active) {
            return back()->withInput()->withErrors([
                'identitas' => 'Akun Anda telah dinonaktifkan. Hubungi Administrator.',
            ]);
        }

        $request->session()->regenerate();

        $rawRole = $pengguna->peran->nama_peran ?? 'staf';
        $role = str_replace(' ', '_', strtolower($rawRole));

        $request->session()->put('pengguna', [
            'id'          => $pengguna->id,
            'nama'        => $pengguna->nama,
            'username'    => $pengguna->username,
            'email'       => $pengguna->email,
            'role'        => $role,
            'raw_role'    => $rawRole,
            'peran_id'    => $pengguna->peran_id,
            'foto_profil' => $pengguna->foto_profil,
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['pengguna', 'penerima']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
