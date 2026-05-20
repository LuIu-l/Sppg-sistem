<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenerimaManfaat;
use App\Models\JadwalDistribusi;
use App\Models\Distribusi;
use App\Models\Pengguna;
use App\Models\Institusi;


class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $jadwalBerikutnya = JadwalDistribusi::with('menuGizi')
            ->where('tanggal_distribusi', '>=', today())
            ->orderBy('tanggal_distribusi')
            ->first();

        // START: Data publik untuk tampilan landing page
        $petugas = Pengguna::with('peran')
            ->whereHas('peran', fn($q) => $q->whereIn('nama_peran', ['Kepala SPPG', 'Ahli Gizi', 'Petugas']))
            ->where('is_active', true)
            ->orderBy('peran_id')
            ->get();

        $institusiList = Institusi::withCount('penerimaManfaat')->orderBy('nama_institusi')->get();
        // END: Data publik

        if ($request->session()->has('penerima')) {
            $sessionPenerima = $request->session()->get('penerima');
            $penerima = PenerimaManfaat::with('institusi')->find($sessionPenerima['id']);

            $riwayatDistribusi = Distribusi::with(['jadwal.menuGizi'])
                ->where('penerima_manfaat_id', $penerima->id)
                ->orderByDesc('waktu_distribusi')
                ->limit(5)
                ->get();

            $sudahMenerimaHariIni = Distribusi::where('penerima_manfaat_id', $penerima->id)
                ->whereDate('waktu_distribusi', today())
                ->where('status', 'terdistribusi')
                ->exists();

            return view('landing', compact(
                'penerima',
                'jadwalBerikutnya',
                'riwayatDistribusi',
                'sudahMenerimaHariIni',
                'petugas',
                'institusiList'
            ));
        }

        return view('landing', compact('jadwalBerikutnya', 'petugas', 'institusiList'));
    }
}
