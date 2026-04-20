<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriCoa extends Model
{
    protected $fillable = ['kode_kategori', 'nama_kategori'];

    public function coas()
    {
        return $this->hasMany(Coa::class, 'kode_kategori', 'kode_kategori');
    }
}
