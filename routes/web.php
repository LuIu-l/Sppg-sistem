<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PenerimaManfaatController;
use App\Http\Controllers\MenuGiziController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\LogistikController;
use App\Http\Controllers\AkunController;
use App\Models\PenerimaManfaat;
use App\Models\MenuGizi;
use App\Models\Distribusi;
use App\Models\StokBahan;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.proses');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    if (!session()->has('pengguna')) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    $role = session('pengguna.role');
    $data = [];

    if (in_array($role, ['kepala_sppg', 'ahli_gizi'])) {
        $data['menuDisetujui'] = MenuGizi::where('status', 'disetujui')->count();
        $data['totalMenu']     = MenuGizi::count();
        $data['menuPending']   = MenuGizi::where('status', 'menunggu')->count();
        $data['menuTerbaru']   = MenuGizi::orderByDesc('created_at')->limit(5)->get();

        $menuAktif = MenuGizi::where('status', 'disetujui')->get();
        $data['statistikGizi'] = [
            'kalori'      => round($menuAktif->avg('total_kalori'), 1),
            'protein'     => round($menuAktif->avg('total_protein'), 1),
            'karbohidrat' => round($menuAktif->avg('total_karbohidrat'), 1),
            'lemak'       => round($menuAktif->avg('total_lemak'), 1),
        ];
    }

    if (in_array($role, ['kepala_sppg', 'petugas'])) {
        $data['totalPenerima']      = PenerimaManfaat::count();
        $data['distribusiHariIni']  = Distribusi::whereDate('waktu_distribusi', today())->count();
        
        $data['stokKritis'] = StokBahan::join('bahan_makanan', 'bahan_makanan.id', '=', 'stok_bahan.bahan_makanan_id')
            ->whereRaw('stok_bahan.stok_aktual <= bahan_makanan.stok_minimum')
            ->count();

        $data['totalPorsiHariIni'] = Distribusi::whereDate('waktu_distribusi', today())
            ->where('status', 'terdistribusi')->count();
        $data['totalPenerimaBelum'] = PenerimaManfaat::where('is_active', true)->count() - $data['totalPorsiHariIni'];
        if ($data['totalPenerimaBelum'] < 0) $data['totalPenerimaBelum'] = 0;

        $data['stokKritisList'] = StokBahan::with('bahanMakanan')
            ->select('stok_bahan.*')
            ->join('bahan_makanan', 'bahan_makanan.id', '=', 'stok_bahan.bahan_makanan_id')
            ->whereRaw('stok_bahan.stok_aktual <= bahan_makanan.stok_minimum')
            ->get();

        // START: Log Aktivitas Terbaru — 5 transaksi terakhir (distribusi)
        $data['logAktivitas'] = Distribusi::with(['penerima', 'jadwal.menuGizi', 'petugas'])
            ->orderByDesc('waktu_distribusi')
            ->limit(5)
            ->get();
    }

    return view('dashboard.index', $data);
})->name('dashboard');

Route::prefix('penerima')->name('penerima.')->group(function () {
    Route::get('/',                          [PenerimaManfaatController::class, 'index'])->name('index');
    Route::get('/tambah',                    [PenerimaManfaatController::class, 'create'])->name('create');
    Route::post('/tambah',                   [PenerimaManfaatController::class, 'store'])->name('store');
    Route::get('/{penerima}/edit',           [PenerimaManfaatController::class, 'edit'])->name('edit');
    Route::put('/{penerima}/edit',           [PenerimaManfaatController::class, 'update'])->name('update');
});

Route::prefix('menu')->name('menu.')->group(function () {
    Route::get('/',                          [MenuGiziController::class, 'index'])->name('index');
    Route::get('/tambah',                    [MenuGiziController::class, 'create'])->name('create');
    Route::post('/tambah',                   [MenuGiziController::class, 'store'])->name('store');
    Route::get('/{menu}/edit',               [MenuGiziController::class, 'edit'])->name('edit');
    Route::put('/{menu}/edit',               [MenuGiziController::class, 'update'])->name('update');
    Route::post('/{menu}/setujui',           [MenuGiziController::class, 'setujui'])->name('setujui');
    Route::post('/{menu}/tolak',             [MenuGiziController::class, 'tolak'])->name('tolak');
});

Route::prefix('distribusi')->name('distribusi.')->group(function () {
    Route::get('/',                          [DistribusiController::class, 'index'])->name('index');
    Route::get('/scan',                      [DistribusiController::class, 'scan'])->name('scan');
    Route::post('/konfirmasi',               [DistribusiController::class, 'konfirmasi'])->name('konfirmasi');
    Route::get('/riwayat',                   [DistribusiController::class, 'riwayat'])->name('riwayat');
    Route::post('/{distribusi}/batalkan',    [DistribusiController::class, 'batalkan'])->name('batalkan');
});

Route::prefix('logistik')->name('logistik.')->group(function () {
    Route::get('/',                          [LogistikController::class, 'index'])->name('index');
    Route::get('/tambah-stok',               [LogistikController::class, 'form'])->name('form');
    Route::post('/tambah-stok',              [LogistikController::class, 'tambahStok'])->name('tambah');
    Route::get('/master-bahan',              [LogistikController::class, 'masterBahan'])->name('master');
    Route::post('/master-bahan',             [LogistikController::class, 'storeBahan'])->name('store-bahan');
});

Route::prefix('akun')->name('akun.')->group(function () {
    Route::get('/',                          [AkunController::class, 'index'])->name('index');
    Route::get('/tambah',                    [AkunController::class, 'form'])->name('create');
    Route::post('/tambah',                   [AkunController::class, 'store'])->name('store');
    Route::get('/{akun}/edit',               [AkunController::class, 'form'])->name('edit');
    Route::put('/{akun}/edit',               [AkunController::class, 'update'])->name('update');
    Route::post('/{akun}/toggle',            [AkunController::class, 'toggleAktif'])->name('toggle');
});

Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/',                          [App\Http\Controllers\ProfilController::class, 'index'])->name('index');
    Route::put('/',                          [App\Http\Controllers\ProfilController::class, 'update'])->name('update');
});
