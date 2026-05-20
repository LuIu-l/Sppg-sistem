<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalDistribusi extends Model
{
    use HasFactory;

    protected $table = 'jadwal_distribusi';

    protected $fillable = [
        'menu_gizi_id',
        'tanggal_distribusi',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'keterangan',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_distribusi' => 'date',
            'is_aktif'           => 'boolean',
        ];
    }

    public function menuGizi()
    {
        return $this->belongsTo(MenuGizi::class, 'menu_gizi_id');
    }

    public function distribusi()
    {
        return $this->hasMany(Distribusi::class, 'jadwal_distribusi_id');
    }
}
