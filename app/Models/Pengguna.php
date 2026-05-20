<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'peran_id',
        'institusi_id',
        'is_active',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function peran()
    {
        return $this->belongsTo(Peran::class, 'peran_id');
    }

    public function institusi()
    {
        return $this->belongsTo(Institusi::class, 'institusi_id');
    }

    public function menuGiziDibuat()
    {
        return $this->hasMany(MenuGizi::class, 'dibuat_oleh');
    }

    public function distribusiDilayani()
    {
        return $this->hasMany(Distribusi::class, 'petugas_id');
    }
}
