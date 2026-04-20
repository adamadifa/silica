<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\JurnalDetail;
use App\Models\SaldoAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\LabaRugiExport;
use App\Exports\PerubahanEkuitasExport;
use App\Exports\NeracaExport;
use App\Traits\HasProjectInspection;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    use HasProjectInspection;

    /**
     * Tampilkan Laporan Buku Besar.
     */
    public function bukuBesar(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $selectedCoaId = $request->coa_id;
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $coas = $perusahaan->coas()->orderBy('kode_akun')->get();
        
        $mutations = [];
        $openingBalance = 0;
        $selectedCoa = null;

        if ($selectedCoaId) {
            $selectedCoa = Coa::findOrFail($selectedCoaId);
            
            // Ambil Saldo Awal periode ini
            $openingBalance = SaldoAwal::where('coa_id', $selectedCoaId)
                ->where('month', $month)
                ->where('year', $year)
                ->value('balance') ?? 0;

            // Ambil Mutasi dari Jurnal
            $mutations = JurnalDetail::where('coa_id', $selectedCoaId)
                ->whereHas('jurnal', function($q) use ($month, $year) {
                    $q->whereMonth('tanggal', $month)
                      ->whereYear('tanggal', $year);
                })
                ->with('jurnal')
                ->get()
                ->sortBy('jurnal.tanggal');
        }

        return view('mahasiswa.laporan.buku-besar', compact('perusahaan', 'coas', 'mutations', 'openingBalance', 'selectedCoa', 'selectedCoaId', 'month', 'year'));
    }

    /**
     * Tampilkan Laporan Neraca Saldo (Trial Balance).
     */
    public function trialBalance(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $reports = $perusahaan->coas()
            ->with(['saldoAwals' => function($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            }])
            ->get()
            ->map(function($coa) use ($month, $year) {
                $opening = $coa->saldoAwals->first()?->balance ?? 0;
                
                $mutation = JurnalDetail::where('coa_id', $coa->id)
                    ->whereHas('jurnal', function($q) use ($month, $year) {
                        $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
                    })
                    ->select(DB::raw('SUM(debit) as total_debit, SUM(kredit) as total_kredit'))
                    ->first();

                $debit = $mutation->total_debit ?? 0;
                $kredit = $mutation->total_kredit ?? 0;

                if ($coa->saldo_normal == 'debit') {
                    $closing = $opening + $debit - $kredit;
                } else {
                    $closing = $opening + $kredit - $debit;
                }

                return (object)[
                    'coa' => $coa,
                    'opening' => $opening,
                    'debit' => $debit,
                    'kredit' => $kredit,
                    'closing' => $closing
                ];
            });

        return view('mahasiswa.laporan.neraca-saldo', compact('perusahaan', 'reports', 'month', 'year'));
    }

    /**
     * Tampilkan Laporan Laba Rugi.
     */
    public function labaRugi(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getLabaRugiData($month, $year);
        return view('mahasiswa.laporan.laba-rugi', $data);
    }

    /**
     * Tampilkan Laporan Laba Rugi versi Cetak.
     */
    public function labaRugiPrint(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getLabaRugiData($month, $year);
        return view('mahasiswa.laporan.laba-rugi-print', $data);
    }

    /**
     * Export Laporan Laba Rugi ke Excel.
     */
    public function labaRugiExcel(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getLabaRugiData($month, $year);
        return Excel::download(new LabaRugiExport($data), 'laporan-laba-rugi.xlsx');
    }

    /**
     * Export Laporan Laba Rugi ke PDF.
     */
    public function labaRugiPdf(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getLabaRugiData($month, $year);
        $pdf = Pdf::loadView('mahasiswa.laporan.laba-rugi-print', $data);
        return $pdf->download('laporan-laba-rugi.pdf');
    }

    /**
     * Tampilkan Laporan Perubahan Ekuitas.
     */
    public function perubahanEkuitas(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getPerubahanEkuitasData($month, $year);
        return view('mahasiswa.laporan.perubahan-ekuitas', $data);
    }

    /**
     * Tampilkan Laporan Perubahan Ekuitas versi Cetak.
     */
    public function perubahanEkuitasPrint(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getPerubahanEkuitasData($month, $year);
        return view('mahasiswa.laporan.perubahan-ekuitas-print', $data);
    }

    /**
     * Export Laporan Perubahan Ekuitas ke Excel.
     */
    public function perubahanEkuitasExcel(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getPerubahanEkuitasData($month, $year);
        return Excel::download(new PerubahanEkuitasExport($data), 'laporan-perubahan-ekuitas.xlsx');
    }

    /**
     * Export Laporan Perubahan Ekuitas ke PDF.
     */
    public function perubahanEkuitasPdf(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getPerubahanEkuitasData($month, $year);
        $pdf = Pdf::loadView('mahasiswa.laporan.perubahan-ekuitas-print', $data);
        return $pdf->download('laporan-perubahan-ekuitas.pdf');
    }

    /**
     * Helper untuk mengambil data Perubahan Ekuitas (Shared logic)
     */
    public function getPerubahanEkuitasData($month = null, $year = null)
    {
        $perusahaan = $this->getActivePerusahaan(request());
        $month = $month ?? $perusahaan->current_month;
        $year = $year ?? $perusahaan->current_year;

        // 1. Ambil Laba/Rugi Bersih
        $labaRugiData = $this->getLabaRugiData($month, $year);
        $laba_rugi_bersih = $labaRugiData['laba_rugi_bersih'];

        // 2. Ambil Modal Serta Prive
        $accounts = $perusahaan->coas()
            ->with(['kategoriCoa', 'saldoAwals' => function($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            }])
            ->whereIn('kode_akun', ['311', '312'])
            ->get();

        $modal_akun = $accounts->where('kode_akun', '311')->first();
        $prive_akun = $accounts->where('kode_akun', '312')->first();

        // Modal Awal (Opening Balance)
        $modal_awal = $modal_akun->saldoAwals->first()->balance ?? 0;

        // Prive (Saldo Awal + Mutasi di bulan berjalan)
        $prive_awal = $prive_akun->saldoAwals->first()->balance ?? 0;
        
        $prive_mutation = JurnalDetail::where('coa_id', $prive_akun->id)
            ->whereHas('jurnal', function($q) use ($month, $year) {
                $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
            })
            ->select(DB::raw('SUM(debit) as total_debit, SUM(kredit) as total_kredit'))
            ->first();
        
        // Prive normalnya debit, jadi (Debit - Kredit)
        $v_prive_mutation = ($prive_mutation->total_debit ?? 0) - ($prive_mutation->total_kredit ?? 0);
        $v_prive = $prive_awal + $v_prive_mutation;

        // Modal Akhir
        $modal_akhir = $modal_awal + $laba_rugi_bersih - $v_prive;

        return compact(
            'perusahaan', 'month', 'year',
            'modal_akun', 'modal_awal',
            'laba_rugi_bersih',
            'prive_akun', 'v_prive',
            'modal_akhir'
        );
    }

    /**
     * Helper untuk mengambil data Laba Rugi (Shared logic)
     */
    public function getLabaRugiData($month = null, $year = null)
    {
        $perusahaan = $this->getActivePerusahaan(request());
        if (!$perusahaan) abort(404, 'Perusahaan tidak ditemukan.');
        $month = $month ?? $perusahaan->current_month;
        $year = $year ?? $perusahaan->current_year;

        // Ambil semua akun pendapatan dan beban
        $rawAccounts = $perusahaan->coas()
            ->with('kategoriCoa')
            ->whereHas('kategoriCoa', function($q) {
                $q->whereIn('nama_kategori', [
                    'Pendapatan', 'Pendapatan Lain-lain', 
                    'Beban', 'Beban Pokok Penjualan', 'Beban Operasi', 'Beban Lain-lain'
                ]);
            })
            ->get()
            ->map(function($coa) use ($month, $year) {
                $mutation = JurnalDetail::where('coa_id', $coa->id)
                    ->whereHas('jurnal', function($q) use ($month, $year) {
                        $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
                    })
                    ->select(DB::raw('SUM(debit) as total_debit, SUM(kredit) as total_kredit'))
                    ->first();

                $debit = $mutation->total_debit ?? 0;
                $kredit = $mutation->total_kredit ?? 0;
                
                $namaKategori = $coa->kategoriCoa->nama_kategori;
                $isPendapatan = str_contains($namaKategori, 'Pendapatan');
                
                // Saldo normal pendapatan = kredit, beban = debit
                $balance = $isPendapatan ? ($kredit - $debit) : ($debit - $kredit);

                return (object)['coa' => $coa, 'balance' => $balance];
            });

        // Grouping berdasarkan format gambar user
        $penjualan = $rawAccounts->where('coa.kode_akun', '411')->first();
        $retur_penjualan = $rawAccounts->where('coa.kode_akun', '412')->first();
        $diskon_penjualan = $rawAccounts->where('coa.kode_akun', '413')->first();
        
        $hpp = $rawAccounts->where('coa.kode_akun', '511')->first();
        $diskon_pembelian = $rawAccounts->where('coa.kode_akun', '512')->first();
        
        $beban_operasional = $rawAccounts->filter(function($i) {
            return str_starts_with($i->coa->kode_akun, '6');
        });

        // Hitung Sub-totals
        $v_penjualan = $penjualan->balance ?? 0;
        $v_retur = $retur_penjualan->balance ?? 0;
        $v_diskon_penjualan = $diskon_penjualan->balance ?? 0;
        $penjualan_bersih = $v_penjualan - $v_retur - $v_diskon_penjualan;

        $v_hpp = $hpp->balance ?? 0;
        $v_diskon_pembelian = $diskon_pembelian->balance ?? 0;
        $hpp_bersih = $v_hpp - $v_diskon_pembelian;

        $laba_kotor = $penjualan_bersih - $hpp_bersih;
        $total_beban = $beban_operasional->sum('balance');
        $laba_rugi_bersih = $laba_kotor - $total_beban;

        return compact(
            'perusahaan', 'month', 'year',
            'penjualan', 'retur_penjualan', 'diskon_penjualan', 'penjualan_bersih',
            'hpp', 'diskon_pembelian', 'hpp_bersih',
            'laba_kotor', 'beban_operasional', 'total_beban', 'laba_rugi_bersih'
        );
    }

    /**
     * Tampilkan Laporan Neraca (Balance Sheet).
     */
    public function neraca(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getNeracaData($month, $year);
        return view('mahasiswa.laporan.neraca', $data);
    }

    /**
     * Tampilkan Laporan Neraca versi Cetak.
     */
    public function neracaPrint(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getNeracaData($month, $year);
        return view('mahasiswa.laporan.neraca-print', $data);
    }

    /**
     * Export Laporan Neraca ke Excel.
     */
    public function neracaExcel(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getNeracaData($month, $year);
        return Excel::download(new NeracaExport($data), 'laporan-posisi-keuangan.xlsx');
    }

    /**
     * Export Laporan Neraca ke PDF.
     */
    public function neracaPdf(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getNeracaData($month, $year);
        $pdf = Pdf::loadView('mahasiswa.laporan.neraca-print', $data);
        return $pdf->download('laporan-posisi-keuangan.pdf');
    }

    /**
     * Tampilkan Laporan Neraca Lajur (Worksheet).
     */
    public function worksheet(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        $month = $request->get('month', $perusahaan->current_month);
        $year = $request->get('year', $perusahaan->current_year);

        $data = $this->getWorksheetData($month, $year);
        return view('mahasiswa.laporan.worksheet', $data);
    }

    /**
     * Helper untuk mengambil data Neraca Lajur (10 Kolom)
     */
    public function getWorksheetData($month = null, $year = null)
    {
        $perusahaan = $this->getActivePerusahaan(request());
        if (!$perusahaan) abort(404, 'Perusahaan tidak ditemukan.');
        $month = $month ?? $perusahaan->current_month;
        $year = $year ?? $perusahaan->current_year;

        $accounts = $perusahaan->coas()
            ->with(['kategoriCoa', 'saldoAwals' => function($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            }])
            ->orderBy('kode_akun')
            ->get();

        $rows = $accounts->map(function($coa) use ($month, $year) {
            $opening = $coa->saldoAwals->first()->balance ?? 0;

            // 1. Get General Journal Mutations (Umum)
            $umum = JurnalDetail::where('coa_id', $coa->id)
                ->whereHas('jurnal', function($q) use ($month, $year) {
                    $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year)
                      ->where('tipe_jurnal', 'Umum');
                })
                ->select(DB::raw('SUM(debit) as total_debit, SUM(kredit) as total_kredit'))
                ->first();

            // 2. Get Adjustment Journal Mutations (Penyesuaian)
            $ajp = JurnalDetail::where('coa_id', $coa->id)
                ->whereHas('jurnal', function($q) use ($month, $year) {
                    $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year)
                      ->where('tipe_jurnal', 'Penyesuaian');
                })
                ->select(DB::raw('SUM(debit) as total_debit, SUM(kredit) as total_kredit'))
                ->first();

            // --- A. NERACA SALDO (NS) ---
            $ns_debit = 0;
            $ns_kredit = 0;
            $ns_raw_balance = ($coa->saldo_normal == 'debit')
                ? ($opening + ($umum->total_debit ?? 0) - ($umum->total_kredit ?? 0))
                : ($opening + ($umum->total_kredit ?? 0) - ($umum->total_debit ?? 0));
            
            if ($coa->saldo_normal == 'debit') {
                if ($ns_raw_balance >= 0) $ns_debit = $ns_raw_balance;
                else $ns_kredit = abs($ns_raw_balance);
            } else {
                if ($ns_raw_balance >= 0) $ns_kredit = $ns_raw_balance;
                else $ns_debit = abs($ns_raw_balance);
            }

            // --- B. PENYESUAIAN (AJP) ---
            $ajp_debit = $ajp->total_debit ?? 0;
            $ajp_kredit = $ajp->total_kredit ?? 0;

            // --- C. NERACA SALDO SETELAH PENYESUAIAN (NSCP) ---
            $nscp_debit = 0;
            $nscp_kredit = 0;
            
            // Total pergerakan bersih (NS + AJP)
            $total_d = $ns_debit + $ajp_debit;
            $total_k = $ns_kredit + $ajp_kredit;

            if ($total_d >= $total_k) $nscp_debit = $total_d - $total_k;
            else $nscp_kredit = $total_k - $total_d;

            // --- D. LABA RUGI (LR) vs NERACA (N) ---
            $cat = $coa->kategoriCoa->nama_kategori;
            $isLR = str_contains($cat, 'Pendapatan') || str_contains($cat, 'Beban');

            $lr_debit = $isLR ? $nscp_debit : 0;
            $lr_kredit = $isLR ? $nscp_kredit : 0;
            
            $n_debit = !$isLR ? $nscp_debit : 0;
            $n_kredit = !$isLR ? $nscp_kredit : 0;

            return (object) [
                'coa' => $coa,
                'ns_debit' => $ns_debit,
                'ns_kredit' => $ns_kredit,
                'ajp_debit' => $ajp_debit,
                'ajp_kredit' => $ajp_kredit,
                'nscp_debit' => $nscp_debit,
                'nscp_kredit' => $nscp_kredit,
                'lr_debit' => $lr_debit,
                'lr_kredit' => $lr_kredit,
                'n_debit' => $n_debit,
                'n_kredit' => $n_kredit,
            ];
        });

        // Totals for Columns
        $totals = (object) [
            'ns_d' => $rows->sum('ns_debit'),
            'ns_k' => $rows->sum('ns_kredit'),
            'ajp_d' => $rows->sum('ajp_debit'),
            'ajp_k' => $rows->sum('ajp_kredit'),
            'nscp_d' => $rows->sum('nscp_debit'),
            'nscp_k' => $rows->sum('nscp_kredit'),
            'lr_d' => $rows->sum('lr_debit'),
            'lr_k' => $rows->sum('lr_kredit'),
            'n_d' => $rows->sum('n_debit'),
            'n_k' => $rows->sum('n_kredit'),
        ];

        // Laba / Rugi Calculation
        $laba_rugi_nominal = $totals->lr_k - $totals->lr_d;

        return compact('perusahaan', 'month', 'year', 'rows', 'totals', 'laba_rugi_nominal');
    }

    /**
     * Helper untuk mengambil data Neraca (Shared logic)
     */
    public function getNeracaData($month = null, $year = null)
    {
        $perusahaan = $this->getActivePerusahaan(request());
        if (!$perusahaan) abort(404, 'Perusahaan tidak ditemukan.');
        $month = $month ?? $perusahaan->current_month;
        $year = $year ?? $perusahaan->current_year;

        // Ambil data Modal Akhir dari Perubahan Ekuitas
        $ekuitasData = $this->getPerubahanEkuitasData($month, $year);
        $modal_akhir = $ekuitasData['modal_akhir'];

        $accounts = $perusahaan->coas()
            ->with(['kategoriCoa', 'saldoAwals' => function($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            }])
            ->get();

        $processedAccounts = $accounts->map(function($coa) use ($month, $year) {
            $opening = $coa->saldoAwals->first()->balance ?? 0;

            $mutation = JurnalDetail::where('coa_id', $coa->id)
                ->whereHas('jurnal', function($q) use ($month, $year) {
                    $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
                })
                ->select(DB::raw('SUM(debit) as total_debit, SUM(kredit) as total_kredit'))
                ->first();

            $debit = $mutation->total_debit ?? 0;
            $kredit = $mutation->total_kredit ?? 0;

            $namaKategori = $coa->kategoriCoa->nama_kategori;
            
            // Tentukan apakah ini kelompok Aset atau Pasiva
            $isAset = str_contains($namaKategori, 'Aset');

            if ($isAset) {
                // Aset normalnya debit, contra-aset (kredit normal) akan jadi negatif
                $balance = $opening + ($coa->saldo_normal == 'debit' ? ($debit - $kredit) : ($kredit - $debit));
                // Namun, agar konsisten dengan perhitungan total, kita gunakan (Debit - Kredit) untuk Aset
                $balance = ($coa->saldo_normal == 'debit') 
                    ? ($opening + $debit - $kredit) 
                    : -($opening + $kredit - $debit);
            } else {
                // Liabilitas/Ekuitas normalnya kredit
                $balance = ($coa->saldo_normal == 'kredit')
                    ? ($opening + $kredit - $debit)
                    : -($opening + $debit - $kredit);
            }

            return (object) [
                'coa' => $coa,
                'balance' => $balance
            ];
        });

        // Grouping Data
        $aset_lancar = $processedAccounts->filter(fn($acc) => in_array($acc->coa->kategoriCoa->nama_kategori, ['Aset Lancar', 'Kas dan Setara Kas', 'Piutang']));
        $aset_tetap = $processedAccounts->filter(fn($acc) => in_array($acc->coa->kategoriCoa->nama_kategori, ['Aset Non Lancar', 'Aset Tetap', 'Investasi Jangka Panjang']));
        $liabilitas = $processedAccounts->filter(fn($acc) => 
            str_contains($acc->coa->kategoriCoa->nama_kategori, 'Liabilitas') || 
            str_contains($acc->coa->kategoriCoa->nama_kategori, 'Utang')
        );

        $total_aset_lancar = $aset_lancar->sum('balance');
        $total_aset_tetap = $aset_tetap->sum('balance');
        $total_aset = $total_aset_lancar + $total_aset_tetap;

        $total_liabilitas = $liabilitas->sum('balance');
        $total_ekuitas = $modal_akhir;

        return compact(
            'perusahaan', 'month', 'year',
            'aset_lancar', 'total_aset_lancar',
            'aset_tetap', 'total_aset_tetap',
            'total_aset',
            'liabilitas', 'total_liabilitas',
            'total_ekuitas'
        );
    }
}
