<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuGizi extends Model
{
    use HasFactory;

    protected $table = 'menu_gizi';

    protected $fillable = [
        'nama_menu',
        'tanggal_berlaku',
        'total_kalori',
        'total_protein',
        'total_karbohidrat',
        'total_lemak',
        'catatan',
        'status',
        'catatan_penolakan',
        'dibuat_oleh',
        'disetujui_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_berlaku'   => 'date',
            'total_kalori'      => 'float',
            'total_protein'     => 'float',
            'total_karbohidrat' => 'float',
            'total_lemak'       => 'float',
        ];
    }

    public function pembuat()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function penyetuju()
    {
        return $this->belongsTo(Pengguna::class, 'disetujui_oleh');
    }

    public function jadwalDistribusi()
    {
        return $this->hasMany(JadwalDistribusi::class, 'menu_gizi_id');
    }

    public function stokBahan()
    {
        return $this->hasMany(StokBahan::class, 'menu_gizi_id');
    }
}
