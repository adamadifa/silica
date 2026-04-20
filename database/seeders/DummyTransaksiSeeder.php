<?php

namespace Database\Seeders;

use App\Models\Coa;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Models\Perusahaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cari user mhs1
        $user = User::where('email', 'mhs1@example.com')->first();
        if (!$user) {
            $this->command->error('User mhs1@example.com tidak ditemukan.');
            return;
        }

        // 2. Cari perusahaan milik mhs1
        $perusahaan = Perusahaan::where('mahasiswa_id', $user->id)->first();
        if (!$perusahaan) {
            $this->command->error('Perusahaan untuk mhs1 tidak ditemukan.');
            return;
        }

        $this->command->info("Seeding data untuk perusahaan: {$perusahaan->nama_perusahaan}");

        // 3. Ambil COA dan petakan berdasarkan kode_akun
        $coas = Coa::where('perusahaan_id', $perusahaan->id)->pluck('id', 'kode_akun');

        // Pastikan COA yang dibutuhkan ada
        $requiredCodes = ['111', '112', '113', '121', '122', '211', '311', '312', '411', '611', '612', '614', '615', '616'];
        foreach ($requiredCodes as $code) {
            if (!$coas->has($code)) {
                $this->command->warn("COA dengan kode {$code} tidak ditemukan. Seeding mungkin gagal untuk beberapa transaksi.");
            }
        }

        $year = $perusahaan->current_year;
        $month = $perusahaan->current_month;

        DB::transaction(function () use ($perusahaan, $coas, $year, $month) {
            // --- DATA SANITIZATION (PENYEIMBANGAN SALDO AWAL) ---
            
            // 1. Ambil semua saldo awal aset & liabilitas saat ini
            $saldoAwals = \App\Models\SaldoAwal::whereIn('coa_id', $perusahaan->coas->pluck('id'))
                ->where('month', $month)
                ->where('year', $year)
                ->get();
            
            $totalAssets = 0;
            $totalLiabilities = 0;
            $modalCoaId = null;
            $openingPriveValue = 0;

            foreach($perusahaan->coas()->with('kategoriCoa')->get() as $coa) {
                $sa = $saldoAwals->where('coa_id', $coa->id)->first();
                $balance = $sa ? $sa->balance : 0;
                $cat = $coa->kategoriCoa->nama_kategori;
                
                if (str_contains($cat, 'Aset')) {
                    $totalAssets += ($coa->saldo_normal == 'debit' ? $balance : -$balance);
                } elseif (str_contains($cat, 'Liabilitas')) {
                    $totalLiabilities += ($coa->saldo_normal == 'kredit' ? $balance : -$balance);
                } elseif ($coa->kode_akun == '311') { // Modal
                    $modalCoaId = $coa->id;
                } elseif ($coa->kode_akun == '312') { // Prive
                    $openingPriveValue = ($coa->saldo_normal == 'debit' ? $balance : -$balance);
                }
                
                // Reset nominal accounts (Pendapatan & Beban)
                if (str_contains($cat, 'Pendapatan') || str_contains($cat, 'Beban')) {
                    if ($sa) {
                        $sa->update(['balance' => 0]);
                    }
                }
            }

            // 2. Hitung Modal yang dibutuhkan agar seimbang (A = L + E)
            // A = L + Modal - Prive  =>  Modal = A - L + Prive
            $requiredModal = $totalAssets - $totalLiabilities + $openingPriveValue;

            if ($modalCoaId) {
                 \App\Models\SaldoAwal::updateOrCreate(
                    ['coa_id' => $modalCoaId, 'month' => $month, 'year' => $year],
                    ['balance' => abs($requiredModal)]
                );
            }

            // Bersihkan jurnal lama agar tidak double
            Jurnal::where('perusahaan_id', $perusahaan->id)->delete();

            // --- JURNAL UMUM ---
            $transaksiUmum = [
                [
                    'tanggal' => Carbon::create($year, $month, 2),
                    'nomor_bukti' => 'BKK-001',
                    'keterangan' => 'Dibayar sewa kantor bulan ini',
                    'tipe_jurnal' => 'Umum',
                    'details' => [
                        ['kode' => '614', 'debit' => 2000000, 'kredit' => 0], // Beban Sewa
                        ['kode' => '111', 'debit' => 0, 'kredit' => 2000000], // Kas
                    ]
                ],
                [
                    'tanggal' => Carbon::create($year, $month, 5),
                    'nomor_bukti' => 'BKM-001',
                    'keterangan' => 'Diterima pendapatan jasa tunai',
                    'tipe_jurnal' => 'Umum',
                    'details' => [
                        ['kode' => '111', 'debit' => 10000000, 'kredit' => 0], // Kas
                        ['kode' => '411', 'debit' => 0, 'kredit' => 10000000], // Penjualan/Pendapatan
                    ]
                ],
                [
                    'tanggal' => Carbon::create($year, $month, 10),
                    'nomor_bukti' => 'BKK-002',
                    'keterangan' => 'Dibeli perlengkapan kantor tunai',
                    'tipe_jurnal' => 'Umum',
                    'details' => [
                        ['kode' => '113', 'debit' => 1500000, 'kredit' => 0], // Perlengkapan
                        ['kode' => '111', 'debit' => 0, 'kredit' => 1500000], // Kas
                    ]
                ],
                [
                    'tanggal' => Carbon::create($year, $month, 15),
                    'nomor_bukti' => 'BM-001',
                    'keterangan' => 'Diselesaikan jasa dengan pembayaran kemudian',
                    'tipe_jurnal' => 'Umum',
                    'details' => [
                        ['kode' => '112', 'debit' => 5000000, 'kredit' => 0], // Piutang
                        ['kode' => '411', 'debit' => 0, 'kredit' => 5000000], // Penjualan
                    ]
                ],
                [
                    'tanggal' => Carbon::create($year, $month, 20),
                    'nomor_bukti' => 'BKK-003',
                    'keterangan' => 'Dibayar gaji karyawan',
                    'tipe_jurnal' => 'Umum',
                    'details' => [
                        ['kode' => '611', 'debit' => 3000000, 'kredit' => 0], // Beban Gaji
                        ['kode' => '111', 'debit' => 0, 'kredit' => 3000000], // Kas
                    ]
                ],
                [
                    'tanggal' => Carbon::create($year, $month, 25),
                    'nomor_bukti' => 'BKK-004',
                    'keterangan' => 'Pengambilan prive oleh pemilik',
                    'tipe_jurnal' => 'Umum',
                    'details' => [
                        ['kode' => '312', 'debit' => 1000000, 'kredit' => 0], // Prive
                        ['kode' => '111', 'debit' => 0, 'kredit' => 1000000], // Kas
                    ]
                ],
            ];

            // --- JURNAL PENYESUAIAN ---
            $transaksiAjp = [
                [
                    'tanggal' => Carbon::create($year, $month, 31),
                    'nomor_bukti' => 'AJP-001',
                    'keterangan' => 'Penyusutan peralatan bulan ini',
                    'tipe_jurnal' => 'Penyesuaian',
                    'details' => [
                        ['kode' => '615', 'debit' => 500000, 'kredit' => 0], // Beban Depresiasi
                        ['kode' => '122', 'debit' => 0, 'kredit' => 500000], // Akum. Penyusutan
                    ]
                ],
                [
                    'tanggal' => Carbon::create($year, $month, 31),
                    'nomor_bukti' => 'AJP-002',
                    'keterangan' => 'Perlengkapan yang terpakai',
                    'tipe_jurnal' => 'Penyesuaian',
                    'details' => [
                        ['kode' => '616', 'debit' => 1000000, 'kredit' => 0], // Beban Perlengkapan
                        ['kode' => '113', 'debit' => 0, 'kredit' => 1000000], // Perlengkapan
                    ]
                ],
            ];

            $allTransaksi = array_merge($transaksiUmum, $transaksiAjp);

            foreach ($allTransaksi as $trx) {
                $jurnal = Jurnal::create([
                    'perusahaan_id' => $perusahaan->id,
                    'tanggal' => $trx['tanggal'],
                    'nomor_bukti' => $trx['nomor_bukti'],
                    'keterangan' => $trx['keterangan'],
                    'tipe_jurnal' => $trx['tipe_jurnal'],
                ]);

                foreach ($trx['details'] as $detail) {
                    if (isset($coas[$detail['kode']])) {
                        JurnalDetail::create([
                            'jurnal_id' => $jurnal->id,
                            'coa_id' => $coas[$detail['kode']],
                            'debit' => $detail['debit'],
                            'kredit' => $detail['kredit'],
                            'keterangan' => $trx['keterangan'],
                        ]);
                    }
                }
            }
        });

        $this->command->info('Dummy data Jurnal Umum dan Penyesuaian berhasil di-seed!');
    }
}
