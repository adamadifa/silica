<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'mahasiswa_id', 
    'kelas_id', 
    'nama_perusahaan', 
    'alamat',
    'telepon',
    'email',
    'logo_path',
    'periode_awal', 
    'periode_akhir', 
    'current_month',
    'current_year',
    'status_pengerjaan', 
    'nilai', 
    'catatan_dosen'
])]
class Perusahaan extends Model
{
    public function getLogoUrlAttribute()
    {
        return $this->logo_path 
            ? asset('storage/' . $this->logo_path) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->nama_perusahaan) . '&background=0284c7&color=fff&size=128';
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function coas()
    {
        return $this->hasMany(Coa::class);
    }

    public function jurnals()
    {
        return $this->hasMany(Jurnal::class);
    }
}
