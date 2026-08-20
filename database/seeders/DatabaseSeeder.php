<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $passwordPegawai = Hash::make('12345');
        $pinSiswa = Hash::make('12345');

        DB::table('peran')->insert([
            ['id' => 1, 'nama_peran' => 'Kepala SPPG'],
            ['id' => 2, 'nama_peran' => 'Ahli Gizi'],
            ['id' => 3, 'nama_peran' => 'Petugas'],
            ['id' => 4, 'nama_peran' => 'Penerima Manfaat'],
        ]);

        DB::table('institusi')->insert([
            ['id' => 1, 'nama_institusi' => 'SD Marhas', 'alamat' => 'Jl. Pendidikan No. 1, Margahayu'],
            ['id' => 2, 'nama_institusi' => 'SMP Marhas', 'alamat' => 'Jl. Pendidikan No. 2, Margahayu'],
            ['id' => 3, 'nama_institusi' => 'SMK Marhas', 'alamat' => 'Jl. Pendidikan No. 3, Margahayu'],
        ]);

        DB::table('pengguna')->insert([
            ['id' => 1, 'peran_id' => 1, 'nama' => 'Ibu Siti Maryam', 'username' => 'kepala_sppg', 'password' => $passwordPegawai],
            
            ['id' => 2, 'peran_id' => 2, 'nama' => 'Ibu Fitri', 'username' => 'ahli_gizi', 'password' => $passwordPegawai],
            
            ['id' => 3, 'peran_id' => 3, 'nama' => 'Pak Ramdani', 'username' => 'petugas', 'password' => $passwordPegawai],
        ]);

        DB::table('penerima_manfaat')->insert([
            ['id' => 1, 'institusi_id' => 1, 'kode_penerima' => 'PM-001', 'nama' => 'Siswa SD Marhas (Alif)', 'nik' => '3204010101000001', 'nisn' => '1234567891', 'tanggal_lahir' => '2010-01-01', 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Pendidikan No. 1, Margahayu', 'pin' => $pinSiswa, 'is_active' => true],
            ['id' => 2, 'institusi_id' => 2, 'kode_penerima' => 'PM-002', 'nama' => 'Siswa SMP Marhas (Alif)', 'nik' => '3204010101000002', 'nisn' => '1234567892', 'tanggal_lahir' => '2010-01-01', 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Pendidikan No. 2, Margahayu', 'pin' => $pinSiswa, 'is_active' => true],
            ['id' => 3, 'institusi_id' => 3, 'kode_penerima' => 'PM-003', 'nama' => 'Siswa SMK Marhas (Alif)', 'nik' => '3204010101000003', 'nisn' => '1234567893', 'tanggal_lahir' => '2010-01-01', 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Pendidikan No. 3, Margahayu', 'pin' => $pinSiswa, 'is_active' => true],
        ]);

        DB::table('bahan_makanan')->insert([
            ['id' => 1, 'nama_bahan' => 'Beras Premium', 'satuan' => 'Kg', 'stok_minimum' => 20],
            ['id' => 2, 'nama_bahan' => 'Daging Sapi', 'satuan' => 'Kg', 'stok_minimum' => 5],
            ['id' => 3, 'nama_bahan' => 'wortel', 'satuan' => 'Kg', 'stok_minimum' => 10],
            ['id' => 4, 'nama_bahan' => 'Susu Kemasan', 'satuan' => 'Kotak', 'stok_minimum' => 50],
        ]);

        DB::table('stok_bahan')->insert([
            ['bahan_makanan_id' => 1, 'stok_aktual' => 150.5],
            ['bahan_makanan_id' => 2, 'stok_aktual' => 3.5], 
            ['bahan_makanan_id' => 3, 'stok_aktual' => 50],
            ['bahan_makanan_id' => 4, 'stok_aktual' => 200],
        ]);

        DB::table('menu_gizi')->insert([
            [
                'id' => 1, 
                'nama_menu' => 'Paket Daging Sehat', 
                'tanggal_berlaku' => $now->format('Y-m-d'),
                'total_kalori' => 650.0, 
                'total_protein' => 25.5, 
                'total_karbohidrat' => 70.0,
                'total_lemak' => 15.0,
                'status' => 'disetujui', 
                'dibuat_oleh' => 2, 
                'created_at' => $now
            ],
            [
                'id' => 2, 
                'nama_menu' => 'Paket Ayam Bergizi', 
                'tanggal_berlaku' => $now->format('Y-m-d'),
                'total_kalori' => 600.0, 
                'total_protein' => 22.0, 
                'total_karbohidrat' => 65.0,
                'total_lemak' => 12.0,
                'status' => 'menunggu', 
                'dibuat_oleh' => 2,
                'created_at' => $now
            ],
            [
                'id' => 3, 
                'nama_menu' => 'Paket pintar gizi', 
                'tanggal_berlaku' => $now->copy()->addDays(1)->format('Y-m-d'),
                'total_kalori' => 580.0, 
                'total_protein' => 28.0, 
                'total_karbohidrat' => 60.0,
                'total_lemak' => 10.0,
                'status' => 'disetujui', 
                'dibuat_oleh' => 2,
                'created_at' => $now
            ],
        ]);

       
        DB::table('jadwal_distribusi')->insert([
            ['id' => 1, 'menu_gizi_id' => 1, 'tanggal_distribusi' => $now->format('Y-m-d')], 
            ['id' => 2, 'menu_gizi_id' => 1, 'tanggal_distribusi' => $now->format('Y-m-d')], 
            ['id' => 3, 'menu_gizi_id' => 1, 'tanggal_distribusi' => $now->format('Y-m-d')], 
        ]);
        
        
        DB::table('distribusi')->insert([
            [
                'jadwal_distribusi_id' => 1, 
                'penerima_manfaat_id' => 1, 
                'waktu_distribusi' => $now->subHours(2), 
                'status' => 'terdistribusi',
                'petugas_id' => 3 
            ],
        ]);
    }
}