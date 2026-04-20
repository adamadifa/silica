<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'coa_id',
    'month',
    'year',
    'balance'
])]
class SaldoAwal extends Model
{
    public function coa()
    {
        return $this->belongsTo(Coa::class);
    }
}
