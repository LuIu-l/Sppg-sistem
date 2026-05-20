<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokBahan extends Model
{
    use HasFactory;

    protected $table = 'stok_bahan';

    protected $fillable = [
        'bahan_makanan_id',
        'menu_gizi_id',
        'stok_aktual',
        'kebutuhan_per_porsi',
        'terakhir_diubah',
    ];

    protected function casts(): array
    {
        return [
            'stok_aktual'         => 'float',
            'kebutuhan_per_porsi' => 'float',
            'terakhir_diubah'     => 'datetime',
        ];
    }

    public function bahanMakanan()
    {
        return $this->belongsTo(BahanMakanan::class, 'bahan_makanan_id');
    }

    public function menuGizi()
    {
        return $this->belongsTo(MenuGizi::class, 'menu_gizi_id');
    }

    public function getIsKritisAttribute(): bool
    {
        return $this->stok_aktual <= ($this->bahanMakanan->stok_minimum ?? 0);
    }
}
