<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coa;
use App\Models\Perusahaan;

class CoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada setidaknya satu perusahaan, jika tidak kita buat perusahaan dummy untuk tujuan testing
        $perusahaan = Perusahaan::first();
        
        if (!$perusahaan) {
            $this->command->warn('Tidak ada data Perusahaan. Silakan jalankan PerusahaanSeeder terlebih dahulu atau buat dari aplikasi.');
            return;
        }

        $coas = Coa::getDefaultCoas();

        foreach ($coas as $coa) {
            Coa::updateOrCreate(
                [
                    'perusahaan_id' => $perusahaan->id,
                    'kode_akun' => $coa['kode_akun']
                ],
                [
                    'nama_akun' => $coa['nama_akun'],
                    'kode_kategori' => $coa['kode_kategori'],
                    'saldo_normal' => $coa['saldo_normal'],
                    'saldo_awal' => $coa['saldo_awal'] ?? 0,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Data COA berhasil di-seed!');
    }
}
