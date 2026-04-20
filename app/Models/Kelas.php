<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['dosen_id', 'nama_kelas', 'tahun_ajaran'])]
class Kelas extends Model
{
    /**
     * Dosen pengampu kelas
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Mahasiswa yang terdaftar di kelas ini
     */
    public function mahasiswas()
    {
        return $this->belongsToMany(User::class, 'kelas_mahasiswa', 'kelas_id', 'mahasiswa_id');
    }

    /**
     * Daftar studi kasus di kelas ini
     */
    public function perusahaans()
    {
        return $this->hasMany(Perusahaan::class);
    }
}
