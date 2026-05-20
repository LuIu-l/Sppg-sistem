<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenerimaManfaat extends Model
{
    use HasFactory;

    protected $table = 'penerima_manfaat';

    protected $fillable = [
        'kode_penerima',
        'nama',
        'nik',
        'nisn',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'institusi_id',
        'pin',
        'is_active',
    ];

    protected $hidden = ['pin'];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'is_active'     => 'boolean',
        ];
    }

    public function institusi()
    {
        return $this->belongsTo(Institusi::class, 'institusi_id');
    }

    public function distribusi()
    {
        return $this->hasMany(Distribusi::class, 'penerima_manfaat_id');
    }
}
