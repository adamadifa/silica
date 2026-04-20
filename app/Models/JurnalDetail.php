<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'jurnal_id', 
    'coa_id', 
    'debit', 
    'kredit', 
    'keterangan'
])]
class JurnalDetail extends Model
{
    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }
}
