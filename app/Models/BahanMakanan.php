<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanMakanan extends Model
{
    use HasFactory;

    protected $table = 'bahan_makanan';

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'kalori_per_satuan',
        'protein_per_satuan',
        'karbohidrat_per_satuan',
        'lemak_per_satuan',
    ];

    protected function casts(): array
    {
        return [
            'kalori_per_satuan'      => 'float',
            'protein_per_satuan'     => 'float',
            'karbohidrat_per_satuan' => 'float',
            'lemak_per_satuan'       => 'float',
        ];
    }

    public function stokBahan()
    {
        return $this->hasMany(StokBahan::class, 'bahan_makanan_id');
    }
}
