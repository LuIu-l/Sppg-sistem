<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peran extends Model
{
    use HasFactory;

    protected $table = 'peran';

    protected $fillable = [
        'nama_peran',
        'deskripsi',
    ];

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'peran_id');
    }
}
