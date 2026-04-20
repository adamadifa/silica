<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'perusahaan_id', 
    'tanggal', 
    'nomor_bukti', 
    'keterangan', 
    'tipe_jurnal'
])]
class Jurnal extends Model
{
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function jurnalDetails()
    {
        return $this->hasMany(JurnalDetail::class);
    }
}
