# Sistem Informasi Manajemen SPPG (Sekolah Program Pemenuhan Gizi)

<p align="center">
  <img src="https://laravel.com/img/logotype.min.svg" width="300" alt="Laravel Logo"/>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"/>
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap"/>
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"/>
  <img src="https://img.shields.io/badge/Database-MySQL%2FSQLite-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
</p>

---

## 📋 Tentang Proyek

Sistem Informasi Manajemen SPPG adalah aplikasi berbasis web yang dibangun menggunakan **Laravel 11** untuk mengelola seluruh alur operasional program pemenuhan gizi, mulai dari manajemen penerima manfaat, perencanaan menu gizi, distribusi makanan, hingga pengelolaan stok bahan makanan di gudang.

Proyek ini dikembangkan sebagai **Tugas Kelompok Akhir Semester** dan arsitekturnya dipetakan secara **1-to-1** terhadap 8 Use Case (UC-01 s/d UC-08) yang telah dianalisis dalam dokumen `sppg_8item.docx`.

---

## 🎯 8 Use Case Utama (UC Mapping)

| ID Use Case | Nama Use Case | Aktor Utama | Controller |
|---|---|---|---|
| **UC-01** | Login & Autentikasi Sistem | Semua Aktor | `LoginController` |
| **UC-02** | Manajemen Data Penerima Manfaat | Petugas Pendaftaran | `PenerimaManfaatController` |
| **UC-03** | Manajemen Menu Gizi & Persetujuan | Ahli Gizi, Kepala SPPG | `MenuGiziController` |
| **UC-04** | Operasional Distribusi & Scan QR | Petugas Distribusi | `DistribusiController` |
| **UC-05** | Manajemen Logistik & Stok Gudang | Petugas Gudang | `LogistikController` |
| **UC-06** | Logout Sistem | Semua Aktor | `LoginController` |
| **UC-07** | Lihat Kartu Digital & QR Code | Penerima Manfaat | `LandingPageController` |
| **UC-08** | Manajemen Akun Pengguna | Kepala SPPG (Admin) | `AkunController` |

---

## 👥 Aktor dan Hak Akses

| Aktor | Metode Login | Redirect Setelah Login | Role di DB |
|---|---|---|---|
| Kepala SPPG | Username / Email + Password | `/dashboard` | `kepala_sppg` |
| Ahli Gizi | Username / Email + Password | `/dashboard` | `ahli_gizi` |
| Petugas Pendaftaran | Username / Email + Password | `/dashboard` | `petugas_pendaftaran` |
| Petugas Distribusi | Username / Email + Password | `/dashboard` | `petugas_distribusi` |
| Petugas Gudang | Username / Email + Password | `/dashboard` | `petugas_gudang` |
| Penerima Manfaat | **NISN (10 digit) / NIK (16 digit) + PIN** | `/` (Landing Page QR Card) | *(tabel terpisah)* |

> ⚠️ **Catatan Penting**: Tidak ada Middleware yang digunakan untuk pengecekan hak akses. Seluruh logika validasi role dan pengalihan rute dilakukan secara kondisional langsung di dalam `LoginController` menggunakan session Laravel standar.

---

## 🗄️ Struktur Database & Skema (9 Tabel Utama)

Berikut adalah detail skema database yang digunakan dalam aplikasi ini untuk keperluan analisis sistem:

### 1. `peran` (Tabel Master Role)
- **id** (PK, BigInt, Auto Increment)
- **nama_peran** (Varchar 100, Unique)
- **deskripsi** (Varchar, Nullable)
- **timestamps** (created_at, updated_at)

### 2. `institusi` (Data Sekolah/Lembaga)
- **id** (PK, BigInt, Auto Increment)
- **nama_institusi** (Varchar)
- **alamat** (Text, Nullable)
- **kota** (Varchar 100, Nullable)
- **nomor_telepon** (Varchar 20, Nullable)
- **is_active** (Boolean, Default: true)
- **timestamps**

### 3. `pengguna` (Akun Staf & Admin)
- **id** (PK, BigInt, Auto Increment)
- **nama** (Varchar)
- **username** (Varchar 100, Unique)
- **email** (Varchar, Unique, Nullable)
- **password** (Varchar)
- **foto_profil** (Varchar, Nullable) - *Ditambahkan melalui migrasi terpisah*
- **peran_id** (FK -> peran.id, Restrict)
- **institusi_id** (FK -> institusi.id, Nullable, Set Null)
- **is_active** (Boolean, Default: true)
- **remember_token** (Varchar 100, Nullable)
- **timestamps**

### 4. `penerima_manfaat` (Data Anak/Siswa)
- **id** (PK, BigInt, Auto Increment)
- **kode_penerima** (Varchar 20, Unique)
- **nama** (Varchar)
- **nik** (Char 16, Unique, Nullable)
- **nisn** (Char 10, Unique, Nullable)
- **tanggal_lahir** (Date)
- **jenis_kelamin** (Enum: L, P)
- **alamat** (Text)
- **institusi_id** (FK -> institusi.id, Nullable, Set Null)
- **pin** (Varchar)
- **is_active** (Boolean, Default: true)
- **timestamps**

### 5. `menu_gizi` (Rencana Menu Harian)
- **id** (PK, BigInt, Auto Increment)
- **nama_menu** (Varchar)
- **tanggal_berlaku** (Date)
- **total_kalori**, **total_protein**, **total_karbohidrat**, **total_lemak** (Decimal 8,2, Default: 0)
- **catatan** (Text, Nullable)
- **status** (Enum: menunggu, disetujui, ditolak, Default: menunggu)
- **catatan_penolakan** (Text, Nullable)
- **dibuat_oleh** (FK -> pengguna.id, Restrict)
- **disetujui_oleh** (FK -> pengguna.id, Nullable, Set Null)
- **timestamps**

### 6. `jadwal_distribusi` (Penjadwalan Pembagian Makanan)
- **id** (PK, BigInt, Auto Increment)
- **menu_gizi_id** (FK -> menu_gizi.id, Restrict)
- **tanggal_distribusi** (Date)
- **waktu_mulai**, **waktu_selesai** (Time, Nullable)
- **lokasi** (Varchar, Nullable)
- **keterangan** (Text, Nullable)
- **is_aktif** (Boolean, Default: true)
- **timestamps**

### 7. `distribusi` (Rekam Jejak/Pengambilan)
- **id** (PK, BigInt, Auto Increment)
- **penerima_manfaat_id** (FK -> penerima_manfaat.id, Restrict)
- **jadwal_distribusi_id** (FK -> jadwal_distribusi.id, Restrict)
- **petugas_id** (FK -> pengguna.id, Restrict)
- **status** (Enum: terdistribusi, dibatalkan, Default: terdistribusi)
- **keterangan** (Text, Nullable)
- **waktu_distribusi** (Timestamp)
- **timestamps**

### 8. `bahan_makanan` (Master Bahan Baku)
- **id** (PK, BigInt, Auto Increment)
- **nama_bahan** (Varchar, Unique)
- **satuan** (Varchar 50)
- **kalori_per_satuan**, **protein_per_satuan**, **karbohidrat_per_satuan**, **lemak_per_satuan** (Decimal 8,2, Default: 0)
- **timestamps**

### 9. `stok_bahan` (Inventaris Gudang)
- **id** (PK, BigInt, Auto Increment)
- **bahan_makanan_id** (FK -> bahan_makanan.id, Restrict)
- **menu_gizi_id** (FK -> menu_gizi.id, Nullable, Set Null)
- **stok_aktual**, **stok_minimum**, **kebutuhan_per_porsi** (Decimal 10,2, Default: 0)
- **terakhir_diubah** (Timestamp, Nullable)
- **timestamps**

### Relasi Antar Tabel (Entity Relationship)

```
pengguna ──── peran (FK: peran_id)
pengguna ──── institusi (FK: institusi_id)
penerima_manfaat ──── institusi (FK: institusi_id)
menu_gizi ──── pengguna (FK: dibuat_oleh, disetujui_oleh)
jadwal_distribusi ──── menu_gizi (FK: menu_gizi_id)
distribusi ──── penerima_manfaat (FK: penerima_manfaat_id)
distribusi ──── jadwal_distribusi (FK: jadwal_distribusi_id)
distribusi ──── pengguna (FK: petugas_id)
stok_bahan ──── bahan_makanan (FK: bahan_makanan_id)
```

---

## 📁 Struktur Direktori Proyek

```
manajemen-sppg/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Controller.php                  # Base Controller
│   │       ├── LoginController.php             # UC-01 & UC-06: Login Fleksibel + Logout
│   │       ├── PenerimaManfaatController.php   # UC-02: CRUD Penerima Manfaat
│   │       ├── MenuGiziController.php          # UC-03: Menu Gizi & Persetujuan
│   │       ├── DistribusiController.php        # UC-04: Operasional Distribusi + QR Scan
│   │       ├── LogistikController.php          # UC-05: Stok Bahan Makanan & Gudang
│   │       ├── LandingPageController.php       # UC-07: Kartu Digital & QR Penerima
│   │       └── AkunController.php              # UC-08: Manajemen Akun Pengguna
│   └── Models/
│       ├── Peran.php                           # Model tabel `peran`
│       ├── Pengguna.php                        # Model tabel `pengguna`
│       ├── Institusi.php                       # Model tabel `institusi`
│       ├── PenerimaManfaat.php                 # Model tabel `penerima_manfaat`
│       ├── MenuGizi.php                        # Model tabel `menu_gizi`
│       ├── JadwalDistribusi.php                # Model tabel `jadwal_distribusi`
│       ├── Distribusi.php                      # Model tabel `distribusi`
│       ├── BahanMakanan.php                    # Model tabel `bahan_makanan`
│       └── StokBahan.php                       # Model tabel `stok_bahan`
├── database/
│   ├── migrations/
│   │   ├── 2026_05_17_000001_create_peran_table.php
│   │   ├── 2026_05_17_000002_create_institusi_table.php
│   │   ├── 2026_05_17_000003_create_pengguna_table.php
│   │   ├── 2026_05_17_000004_create_penerima_manfaat_table.php
│   │   ├── 2026_05_17_000005_create_menu_gizi_table.php
│   │   ├── 2026_05_17_000006_create_jadwal_distribusi_table.php
│   │   ├── 2026_05_17_000007_create_distribusi_table.php
│   │   ├── 2026_05_17_000008_create_bahan_makanan_table.php
│   │   └── 2026_05_17_000009_create_stok_bahan_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php                   # Layout utama staf (AdminLTE v4.0.0-rc7)
│       │   └── guest.blade.php                 # Layout halaman publik/login
│       ├── auth/
│       │   └── login.blade.php                 # UC-01: Form login fleksibel (Username/NIK/NISN)
│       ├── dashboard/
│       │   └── index.blade.php                 # Dashboard staf (ringkasan data)
│       ├── penerima/                           # UC-02: Manajemen Penerima Manfaat
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── menu/                               # UC-03: Menu Gizi
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── distribusi/                         # UC-04: Distribusi & QR Scan
│       │   ├── index.blade.php
│       │   └── scan.blade.php
│       ├── logistik/                           # UC-05: Gudang & Stok
│       │   ├── index.blade.php
│       │   └── form.blade.php
│       ├── akun/                               # UC-08: Manajemen Akun
│       │   ├── index.blade.php
│       │   └── form.blade.php
│       └── landing.blade.php                   # UC-07: Kartu Digital QR Penerima Manfaat
└── routes/
    └── web.php                                 # Semua rute terstruktur per UC
```

---

## 🚀 Cara Instalasi & Menjalankan

### Prasyarat
- PHP 8.2+
- Composer
- MySQL atau SQLite
- Node.js & NPM (opsional untuk asset compilation)

### Langkah Instalasi

```bash
# 1. Clone atau masuk ke direktori proyek
cd "tugas kelompok akhir semester/manajemen-sppg"

# 2. Install dependensi PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di file .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=manajemen_sppg
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan seluruh migrasi (9 tabel utama)
php artisan migrate:fresh --seed

# 7. Jalankan server development
php artisan serve
```

### Akun Default (Seeder)

| Nama | Peran | Identitas Login | Password/PIN |
|---|---|---|---|
| Admin SPPG | Kepala SPPG | `admin` | `password123` |
| Dr. Gizi | Ahli Gizi | `ahligizi` | `password123` |
| Budi Santoso | Petugas Pendaftaran | `petugas1` | `password123` |
| Siti Rahayu | Penerima Manfaat | NIK: `3201010101010001` | `1234` |
| Ahmad Fauzi | Penerima Manfaat | NISN: `0012345678` | `5678` |

---

## 🔐 Logika Autentikasi (UC-01 & UC-06)

### Alur Login Fleksibel di `LoginController`

```
Input oleh pengguna → LoginController::login()
  │
  ├─► Cek apakah input identitas berupa 10 digit angka (NISN)?
  │     └─► Ya → Cari di tabel `penerima_manfaat` (kolom `nisn`)
  │               → Cocokkan PIN → Set session `penerima` → Redirect ke `/` (Landing QR)
  │
  ├─► Cek apakah input identitas berupa 16 digit angka (NIK)?
  │     └─► Ya → Cari di tabel `penerima_manfaat` (kolom `nik`)
  │               → Cocokkan PIN → Set session `penerima` → Redirect ke `/` (Landing QR)
  │
  └─► Selain itu → Cari di tabel `pengguna` (kolom `username` atau `email`)
        → Cocokkan Password (Hash) → Set session `pengguna`
        → Cek is_active → Cek role → Redirect ke `/dashboard`
```

> **Tidak ada Middleware** yang digunakan. Validasi dilakukan murni secara kondisional di controller.

---

## 🔗 UC-04 ↔ UC-05: Integrasi Otomatis Stok Gudang

Saat konfirmasi distribusi berhasil (UC-04), sistem secara otomatis:
1. Memotong stok bahan makanan terkait di tabel `stok_bahan` (UC-05)
2. Mencatat log pemotongan stok
3. Mencegah distribusi ganda: satu penerima hanya bisa scan QR **satu kali per hari**

---

## 🎨 Panduan Desain UI/UX

Sistem informasi operasional ini didesain untuk terlihat bersih, serius, dan minimalis seperti panel admin profesional,terintegrasi dengan **AdminLTE v4.0.0-rc7**:

- **Wajib AdminLTE v4 + Bootstrap 5**: Seluruh layouting dan styling HARUS menggunakan kelas utilitas asli AdminLTE 4 dan Bootstrap 5 (contoh: `app-wrapper`, `sidebar-wrapper`, `bg-body-tertiary`) yang dipanggil via CDN.
- **Eksternal CSS DILARANG**: Modifikasi menggunakan file CSS eksternal sangat dilarang. Penggunaan Tailwind CSS dilarang keras, wajib menggunakan utilitas Bootstrap secara eksklusif.
- **Desain Minimalis**: Dilarang menggunakan desain *Glassmorphism*, warna tema abstrak (*Gradient Blur/Blue*), efek *backdrop-filter: blur*, animasi *micro-animation* berlebihan, maupun efek transparan buram.
- **Tipografi**: Google Fonts — `Inter` atau `Poppins` atau font bawaan sistem yang bersih dan terbaca jelas.
- **Fokus Utama**: Menjaga integritas profesional fungsionalitas panel admin yang rapi (Pristine), memprioritaskan fungsi, kecepatan dan utilitas Bootstrap murni.

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik. Dilarang mempublikasikan atau menggunakan untuk keperluan komersial tanpa izin.
