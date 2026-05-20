<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Institusi extends Model
{
    use HasFactory;

    protected $table = 'institusi';

    protected $fillable = [
        'nama_institusi',
        'alamat',
        'kota',
        'nomor_telepon',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'institusi_id');
    }

    public function penerimaManfaat()
    {
        return $this->hasMany(PenerimaManfaat::class, 'institusi_id');
    }
}
