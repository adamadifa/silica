<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\SaldoAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClosingController extends Controller
{
    /**
     * Proses Tutup Buku untuk periode saat ini.
     */
    public function store()
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $month = $perusahaan->current_month;
        $year = $perusahaan->current_year;

        // 1. Validasi Keseimbangan Laporan Posisi Keuangan (Neraca)
        $laporanController = new LaporanController();
        $neracaData = $laporanController->getNeracaData($month, $year);

        $totalAset = $neracaData['total_aset'];
        $totalPasiva = $neracaData['total_liabilitas'] + $neracaData['total_ekuitas'];
        $diff = abs($totalAset - $totalPasiva);

        if ($diff > 1) { // Toleransi 1 Rupiah
            return back()->with('error', 'Gagal Tutup Buku! Laporan Posisi Keuangan tidak seimbang (Unbalanced). Selisih: Rp ' . number_format($diff) . '. Pastikan Jurnal Umum, Jurnal Penyesuaian, dan Saldo Awal sudah benar.');
        }

        // 2. Hitung Saldo Akhir untuk setiap akun dan simpan sebagai Saldo Awal bulan depan
        DB::transaction(function () use ($perusahaan, $month, $year) {
            $coas = $perusahaan->coas;
            
            // Hitung tanggal untuk periode berikutnya
            $currentDate = Carbon::createFromDate($year, $month, 1);
            $nextDate = $currentDate->copy()->addMonth();

            foreach ($coas as $coa) {
                // Ambil saldo awal periode ini
                $openingBalance = SaldoAwal::where('coa_id', $coa->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->value('balance') ?? $coa->saldo_awal ?? 0;

                // Hitung total mutasi
                $mutation = DB::table('jurnal_details')
                    ->join('jurnals', 'jurnal_details.jurnal_id', '=', 'jurnals.id')
                    ->where('jurnal_details.coa_id', $coa->id)
                    ->whereMonth('jurnals.tanggal', $month)
                    ->whereYear('jurnals.tanggal', $year)
                    ->select(DB::raw('SUM(debit) as total_debit, SUM(kredit) as total_kredit'))
                    ->first();

                $debitMutation = $mutation->total_debit ?? 0;
                $kreditMutation = $mutation->total_kredit ?? 0;

                if ($coa->saldo_normal == 'debit') {
                    $closingBalance = $openingBalance + $debitMutation - $kreditMutation;
                } else {
                    $closingBalance = $openingBalance + $kreditMutation - $debitMutation;
                }

                // Simpan sebagai saldo awal bulan depan
                SaldoAwal::updateOrCreate(
                    [
                        'coa_id' => $coa->id,
                        'month' => $nextDate->month,
                        'year' => $nextDate->year,
                    ],
                    [
                        'balance' => $closingBalance
                    ]
                );
            }

            // 3. Update periode aktif perusahaan
            $perusahaan->update([
                'current_month' => $nextDate->month,
                'current_year' => $nextDate->year,
            ]);
        });

        return back()->with('success', 'Tutup Buku berhasil! Sekarang Anda berada di periode ' . Carbon::createFromDate($perusahaan->current_year, $perusahaan->current_month, 1)->format('F Y'));
    }
}
