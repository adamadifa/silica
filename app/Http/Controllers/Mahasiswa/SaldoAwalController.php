<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\SaldoAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\HasProjectInspection;

class SaldoAwalController extends Controller
{
    use HasProjectInspection;

    /**
     * Tampilkan form input saldo awal.
     */
    public function index(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        // Ambil data akun beserta saldo awalnya di periode mulai
        $coas = $perusahaan->coas()
            ->with(['saldoAwals' => function($query) use ($perusahaan) {
                $query->where('month', $perusahaan->current_month)
                      ->where('year', $perusahaan->current_year);
            }])
            ->orderBy('kode_akun')
            ->get();

        return view('mahasiswa.saldo-awal.index', compact('perusahaan', 'coas'));
    }

    /**
     * Simpan/Update saldo awal secara batch.
     */
    public function store(Request $request)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $request->validate([
            'balances' => 'required|array',
            'balances.*' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request, $perusahaan) {
            foreach ($request->balances as $coaId => $balance) {
                SaldoAwal::updateOrCreate(
                    [
                        'coa_id' => $coaId,
                        'month' => $perusahaan->current_month,
                        'year' => $perusahaan->current_year,
                    ],
                    [
                        'balance' => $balance
                    ]
                );
            }
        });

        return redirect()->route('mahasiswa.saldo-awal.index')->with('success', 'Saldo awal berhasil diperbarui.');
    }
}
