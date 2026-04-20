<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\HasProjectInspection;

class JurnalController extends Controller
{
    use HasProjectInspection;

    /**
     * Tampilkan daftar transaksi jurnal umum.
     */
    public function index(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $jurnals = $perusahaan->jurnals()
            ->where('tipe_jurnal', 'Umum')
            ->whereMonth('tanggal', $perusahaan->current_month)
            ->whereYear('tanggal', $perusahaan->current_year)
            ->with(['jurnalDetails.coa'])
            ->latest('tanggal')
            ->latest('id')
            ->get();

        return view('mahasiswa.jurnal.index', compact('perusahaan', 'jurnals'));
    }

    /**
     * Form entri jurnal baru.
     */
    public function create()
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        
        $coas = $perusahaan->coas()
            ->orderBy('kode_akun')
            ->get();

        return view('mahasiswa.jurnal.create', compact('perusahaan', 'coas'));
    }

    /**
     * Simpan transaksi jurnal baru.
     */
    public function store(Request $request)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $request->validate([
            'tanggal' => 'required|date',
            'nomor_bukti' => 'required|string|max:50',
            'keterangan' => 'required|string|max:255',
            'details' => 'required|array|min:2',
            'details.*.coa_id' => 'required|exists:coas,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.kredit' => 'required|numeric|min:0',
        ]);

        // Validasi Balance (Debit = Kredit)
        $totalDebit = collect($request->details)->sum('debit');
        $totalKredit = collect($request->details)->sum('kredit');

        if (abs($totalDebit - $totalKredit) > 0.01) {
            return back()->withInput()->with('error', 'Transaksi tidak seimbang! Total Debit (' . number_format($totalDebit) . ') harus sama dengan Total Kredit (' . number_format($totalKredit) . ').');
        }

        DB::transaction(function () use ($request, $perusahaan) {
            $jurnal = $perusahaan->jurnals()->create([
                'tanggal' => $request->tanggal,
                'nomor_bukti' => $request->nomor_bukti,
                'keterangan' => $request->keterangan,
                'tipe_jurnal' => 'Umum',
            ]);

            foreach ($request->details as $detail) {
                if ($detail['debit'] > 0 || $detail['kredit'] > 0) {
                    $jurnal->jurnalDetails()->create([
                        'coa_id' => $detail['coa_id'],
                        'debit' => $detail['debit'],
                        'kredit' => $detail['kredit'],
                    ]);
                }
            }
        });

        return redirect()->route('mahasiswa.jurnal.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    /**
     * Form edit jurnal.
     */
    public function edit(Jurnal $jurnal)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        $this->authorizeOwner($perusahaan);
        
        if ($jurnal->perusahaan_id !== $perusahaan->id || $jurnal->tipe_jurnal !== 'Umum') {
            abort(403);
        }

        $coas = $perusahaan->coas()->orderBy('kode_akun')->get();
        return view('mahasiswa.jurnal.edit', compact('perusahaan', 'coas', 'jurnal'));
    }

    /**
     * Update transaksi jurnal.
     */
    public function update(Request $request, Jurnal $jurnal)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        $this->authorizeOwner($perusahaan);

        if ($jurnal->perusahaan_id !== $perusahaan->id) abort(403);

        $request->validate([
            'tanggal' => 'required|date',
            'nomor_bukti' => 'required|string|max:50',
            'keterangan' => 'required|string|max:255',
            'details' => 'required|array|min:2',
            'details.*.coa_id' => 'required|exists:coas,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.kredit' => 'required|numeric|min:0',
        ]);

        $totalDebit = collect($request->details)->sum('debit');
        $totalKredit = collect($request->details)->sum('kredit');

        if (abs($totalDebit - $totalKredit) > 0.01) {
            return back()->withInput()->with('error', 'Transaksi tidak seimbang!');
        }

        DB::transaction(function () use ($request, $jurnal) {
            $jurnal->update([
                'tanggal' => $request->tanggal,
                'nomor_bukti' => $request->nomor_bukti,
                'keterangan' => $request->keterangan,
            ]);

            // Re-sync details: simple way is to delete and re-create
            $jurnal->jurnalDetails()->delete();

            foreach ($request->details as $detail) {
                if ($detail['debit'] > 0 || $detail['kredit'] > 0) {
                    $jurnal->jurnalDetails()->create([
                        'coa_id' => $detail['coa_id'],
                        'debit' => $detail['debit'],
                        'kredit' => $detail['kredit'],
                    ]);
                }
            }
        });

        return redirect()->route('mahasiswa.jurnal.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Hapus transaksi jurnal.
     */
    public function destroy(Jurnal $jurnal)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        $this->authorizeOwner($perusahaan);

        if ($jurnal->perusahaan_id !== $perusahaan->id) abort(403);

        $jurnal->delete(); // Cascade delete usually handled at DB level or Model level if using boot()

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Tampilkan daftar transaksi jurnal penyesuaian.
     */
    public function adjIndex(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $jurnals = $perusahaan->jurnals()
            ->where('tipe_jurnal', 'Penyesuaian')
            ->whereMonth('tanggal', $perusahaan->current_month)
            ->whereYear('tanggal', $perusahaan->current_year)
            ->with(['jurnalDetails.coa'])
            ->latest('tanggal')
            ->latest('id')
            ->get();

        return view('mahasiswa.jurnal-penyesuaian.index', compact('perusahaan', 'jurnals'));
    }

    /**
     * Form entri jurnal penyesuaian baru.
     */
    public function adjCreate()
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');
        
        $coas = $perusahaan->coas()
            ->orderBy('kode_akun')
            ->get();

        return view('mahasiswa.jurnal-penyesuaian.create', compact('perusahaan', 'coas'));
    }

    /**
     * Simpan transaksi jurnal penyesuaian baru.
     */
    public function adjStore(Request $request)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $request->validate([
            'tanggal' => 'required|date',
            'nomor_bukti' => 'required|string|max:50',
            'keterangan' => 'required|string|max:255',
            'details' => 'required|array|min:2',
            'details.*.coa_id' => 'required|exists:coas,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.kredit' => 'required|numeric|min:0',
        ]);

        // Validasi Balance (Debit = Kredit)
        $totalDebit = collect($request->details)->sum('debit');
        $totalKredit = collect($request->details)->sum('kredit');

        if (abs($totalDebit - $totalKredit) > 0.01) {
            return back()->withInput()->with('error', 'Transaksi tidak seimbang! Total Debit (' . number_format($totalDebit) . ') harus sama dengan Total Kredit (' . number_format($totalKredit) . ').');
        }

        DB::transaction(function () use ($request, $perusahaan) {
            $jurnal = $perusahaan->jurnals()->create([
                'tanggal' => $request->tanggal,
                'nomor_bukti' => $request->nomor_bukti,
                'keterangan' => $request->keterangan,
                'tipe_jurnal' => 'Penyesuaian',
            ]);

            foreach ($request->details as $detail) {
                if ($detail['debit'] > 0 || $detail['kredit'] > 0) {
                    $jurnal->jurnalDetails()->create([
                        'coa_id' => $detail['coa_id'],
                        'debit' => $detail['debit'],
                        'kredit' => $detail['kredit'],
                    ]);
                }
            }
        });

        return redirect()->route('mahasiswa.jurnal-penyesuaian.index')->with('success', 'Jurnal penyesuaian berhasil dicatat.');
    }

    /**
     * Form edit jurnal penyesuaian.
     */
    public function adjEdit(Jurnal $jurnal)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        $this->authorizeOwner($perusahaan);
        
        if ($jurnal->perusahaan_id !== $perusahaan->id || $jurnal->tipe_jurnal !== 'Penyesuaian') {
            abort(403);
        }

        $coas = $perusahaan->coas()->orderBy('kode_akun')->get();
        return view('mahasiswa.jurnal-penyesuaian.edit', compact('perusahaan', 'coas', 'jurnal'));
    }

    /**
     * Update jurnal penyesuaian.
     */
    public function adjUpdate(Request $request, Jurnal $jurnal)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        $this->authorizeOwner($perusahaan);

        if ($jurnal->perusahaan_id !== $perusahaan->id) abort(403);

        $request->validate([
            'tanggal' => 'required|date',
            'nomor_bukti' => 'required|string|max:50',
            'keterangan' => 'required|string|max:255',
            'details' => 'required|array|min:2',
            'details.*.coa_id' => 'required|exists:coas,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.kredit' => 'required|numeric|min:0',
        ]);

        $totalDebit = collect($request->details)->sum('debit');
        $totalKredit = collect($request->details)->sum('kredit');

        if (abs($totalDebit - $totalKredit) > 0.01) {
            return back()->withInput()->with('error', 'Penyusuaian tidak seimbang!');
        }

        DB::transaction(function () use ($request, $jurnal) {
            $jurnal->update([
                'tanggal' => $request->tanggal,
                'nomor_bukti' => $request->nomor_bukti,
                'keterangan' => $request->keterangan,
            ]);

            $jurnal->jurnalDetails()->delete();

            foreach ($request->details as $detail) {
                if ($detail['debit'] > 0 || $detail['kredit'] > 0) {
                    $jurnal->jurnalDetails()->create([
                        'coa_id' => $detail['coa_id'],
                        'debit' => $detail['debit'],
                        'kredit' => $detail['kredit'],
                    ]);
                }
            }
        });

        return redirect()->route('mahasiswa.jurnal-penyesuaian.index')->with('success', 'Jurnal penyesuaian berhasil diperbarui.');
    }

    /**
     * Hapus jurnal penyesuaian.
     */
    public function adjDestroy(Jurnal $jurnal)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        $this->authorizeOwner($perusahaan);

        if ($jurnal->perusahaan_id !== $perusahaan->id) abort(403);

        $jurnal->delete();

        return back()->with('success', 'Jurnal penyesuaian berhasil dihapus.');
    }

    /**
     * Private helper untuk otorisasi pemilik perusahaan.
     */
    private function authorizeOwner(Perusahaan $perusahaan)
    {
        if ($perusahaan->mahasiswa_id !== Auth::id() && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
    }
}
