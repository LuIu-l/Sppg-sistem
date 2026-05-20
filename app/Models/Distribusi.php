<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distribusi extends Model
{
    use HasFactory;

    protected $table = 'distribusi';

    protected $fillable = [
        'penerima_manfaat_id',
        'jadwal_distribusi_id',
        'petugas_id',
        'status',
        'keterangan',
        'waktu_distribusi',
    ];

    protected function casts(): array
    {
        return ['waktu_distribusi' => 'datetime'];
    }

    public function penerima()
    {
        return $this->belongsTo(PenerimaManfaat::class, 'penerima_manfaat_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalDistribusi::class, 'jadwal_distribusi_id');
    }

    public function petugas()
    {
        return $this->belongsTo(Pengguna::class, 'petugas_id');
    }
}
