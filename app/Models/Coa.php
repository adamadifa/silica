<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'perusahaan_id', 
    'kode_akun', 
    'nama_akun', 
    'kode_kategori', 
    'saldo_normal', 
    'saldo_awal', 
    'is_active'
])]
class Coa extends Model
{
    /**
     * Nama tabel eksplisit (karena jamak COA sering membingungkan pluralizer)
     */
    protected $table = 'coas';

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function kategoriCoa()
    {
        return $this->belongsTo(KategoriCoa::class, 'kode_kategori', 'kode_kategori');
    }

    public function jurnalDetails()
    {
        return $this->hasMany(JurnalDetail::class);
    }

    public function saldoAwals()
    {
        return $this->hasMany(SaldoAwal::class);
    }

    public static function getDefaultCoas()
    {
        return [
            // Aset Lancar
            ['kode_akun' => '111', 'nama_akun' => 'Kas', 'kode_kategori' => 'AL', 'saldo_normal' => 'debit', 'saldo_awal' => 150000000],
            ['kode_akun' => '112', 'nama_akun' => 'Piutang', 'kode_kategori' => 'AL', 'saldo_normal' => 'debit', 'saldo_awal' => 375000000],
            ['kode_akun' => '113', 'nama_akun' => 'Perlengkapan', 'kode_kategori' => 'AL', 'saldo_normal' => 'debit', 'saldo_awal' => 50000000],
            ['kode_akun' => '114', 'nama_akun' => 'Persediaan Barang Dagang', 'kode_kategori' => 'AL', 'saldo_normal' => 'debit', 'saldo_awal' => 1700000000],
            ['kode_akun' => '115', 'nama_akun' => 'Asuransi Dibayar Dimuka', 'kode_kategori' => 'AL', 'saldo_normal' => 'debit', 'saldo_awal' => 300000000],
            ['kode_akun' => '116', 'nama_akun' => 'Sewa Dibayar Dimuka', 'kode_kategori' => 'AL', 'saldo_normal' => 'debit', 'saldo_awal' => 0],

            // Aset Non Lancar
            ['kode_akun' => '121', 'nama_akun' => 'Peralatan', 'kode_kategori' => 'ANL', 'saldo_normal' => 'debit', 'saldo_awal' => 240000000],
            ['kode_akun' => '122', 'nama_akun' => 'Akumulasi Penyusutan Peralatan', 'kode_kategori' => 'ANL', 'saldo_normal' => 'kredit', 'saldo_awal' => 48000000],

            // Liabilitas Lancar
            ['kode_akun' => '211', 'nama_akun' => 'Utang Dagang', 'kode_kategori' => 'LL', 'saldo_normal' => 'kredit', 'saldo_awal' => 650000000],
            ['kode_akun' => '212', 'nama_akun' => 'Utang Gaji', 'kode_kategori' => 'LL', 'saldo_normal' => 'kredit', 'saldo_awal' => 0],
            ['kode_akun' => '213', 'nama_akun' => 'Utang Listrik', 'kode_kategori' => 'LL', 'saldo_normal' => 'kredit', 'saldo_awal' => 0],

            // Ekuitas Pemilik
            ['kode_akun' => '311', 'nama_akun' => 'Modal Tn (Nama Anda)', 'kode_kategori' => 'EP', 'saldo_normal' => 'kredit', 'saldo_awal' => 2527000000],
            ['kode_akun' => '312', 'nama_akun' => 'Prive Tn (Nama Anda)', 'kode_kategori' => 'EP', 'saldo_normal' => 'debit', 'saldo_awal' => 10000000],

            // Pendapatan
            ['kode_akun' => '411', 'nama_akun' => 'Penjualan', 'kode_kategori' => 'P', 'saldo_normal' => 'kredit', 'saldo_awal' => 750000000],
            ['kode_akun' => '412', 'nama_akun' => 'Retur Penjualan', 'kode_kategori' => 'P', 'saldo_normal' => 'debit', 'saldo_awal' => 50000000],
            ['kode_akun' => '413', 'nama_akun' => 'Diskon Penjualan', 'kode_kategori' => 'P', 'saldo_normal' => 'debit', 'saldo_awal' => 25000000],

            // Beban Pokok Penjualan
            ['kode_akun' => '511', 'nama_akun' => 'Harga Pokok Penjualan', 'kode_kategori' => 'BPP', 'saldo_normal' => 'debit', 'saldo_awal' => 540000000],
            ['kode_akun' => '512', 'nama_akun' => 'Diskon Pembelian', 'kode_kategori' => 'BPP', 'saldo_normal' => 'kredit', 'saldo_awal' => 25000000],

            // Beban Operasi
            ['kode_akun' => '611', 'nama_akun' => 'Beban Gaji', 'kode_kategori' => 'BO', 'saldo_normal' => 'debit', 'saldo_awal' => 176000000],
            ['kode_akun' => '612', 'nama_akun' => 'Beban Listrik', 'kode_kategori' => 'BO', 'saldo_normal' => 'debit', 'saldo_awal' => 60000000],
            ['kode_akun' => '613', 'nama_akun' => 'Beban Asuransi', 'kode_kategori' => 'BO', 'saldo_normal' => 'debit', 'saldo_awal' => 0],
            ['kode_akun' => '614', 'nama_akun' => 'Beban Sewa', 'kode_kategori' => 'BO', 'saldo_normal' => 'debit', 'saldo_awal' => 300000000],
            ['kode_akun' => '615', 'nama_akun' => 'Beban Depresiasi', 'kode_kategori' => 'BO', 'saldo_normal' => 'debit', 'saldo_awal' => 24000000],
            ['kode_akun' => '616', 'nama_akun' => 'Beban Perlengkapan', 'kode_kategori' => 'BO', 'saldo_normal' => 'debit', 'saldo_awal' => 0],
        ];
    }
}
