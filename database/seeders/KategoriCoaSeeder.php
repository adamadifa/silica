<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriCoa;

class KategoriCoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['kode_kategori' => 'AL', 'nama_kategori' => 'Aset Lancar'],
            ['kode_kategori' => 'IJP', 'nama_kategori' => 'Investasi Jangka Panjang'],
            ['kode_kategori' => 'ANL', 'nama_kategori' => 'Aset Non Lancar'],
            ['kode_kategori' => 'LL', 'nama_kategori' => 'Liabilitas Lancar'],
            ['kode_kategori' => 'LNL', 'nama_kategori' => 'Liabilitas Non Lancar'],
            ['kode_kategori' => 'EP', 'nama_kategori' => 'Ekuitas Pemilik'],
            ['kode_kategori' => 'P', 'nama_kategori' => 'Pendapatan'],
            ['kode_kategori' => 'BPP', 'nama_kategori' => 'Beban Pokok Penjualan'],
            ['kode_kategori' => 'BO', 'nama_kategori' => 'Beban Operasi'],
            ['kode_kategori' => 'PLL', 'nama_kategori' => 'Pendapatan Lain-lain'],
            ['kode_kategori' => 'BLL', 'nama_kategori' => 'Beban Lain-lain'],
            
            // Backup for simple categories just in case
            ['kode_kategori' => 'AT', 'nama_kategori' => 'Aset Tetap'],
            ['kode_kategori' => 'L', 'nama_kategori' => 'Liabilitas'],
            ['kode_kategori' => 'E', 'nama_kategori' => 'Ekuitas'],
            ['kode_kategori' => 'B', 'nama_kategori' => 'Beban'],
        ];

        foreach ($kategori as $kat) {
            KategoriCoa::updateOrCreate(
                ['kode_kategori' => $kat['kode_kategori']],
                ['nama_kategori' => $kat['nama_kategori']]
            );
        }
    }
}
