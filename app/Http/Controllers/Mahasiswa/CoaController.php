<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\Perusahaan;
use App\Imports\CoaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasProjectInspection;

class CoaController extends Controller
{
    use HasProjectInspection;

    /**
     * Tampilkan daftar akun (COA) untuk perusahaan milik mahasiswa.
     */
    public function index(Request $request)
    {
        $perusahaan = $this->getActivePerusahaan($request);
        
        if (!$perusahaan) {
            return redirect()->route('mahasiswa.perusahaan.create')->with('info', 'Silakan setup profil perusahaan Anda terlebih dahulu.');
        }

        $coas = $perusahaan->coas()
            ->orderBy('kode_akun')
            ->get();

        return view('mahasiswa.coa.index', compact('perusahaan', 'coas'));
    }

    /**
     * Tampilkan form tambah akun.
     */
    public function create()
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $kategoriCoas = \App\Models\KategoriCoa::orderBy('kode_kategori')->get();
        return view('mahasiswa.coa.create', compact('perusahaan', 'kategoriCoas'));
    }

    /**
     * Simpan akun baru.
     */
    public function store(Request $request)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $request->validate([
            'kode_akun' => 'required|string|max:20',
            'nama_akun' => 'required|string|max:255',
            'kode_kategori' => 'required|string|exists:kategori_coas,kode_kategori',
            'saldo_normal' => 'required|in:debit,kredit',
        ]);

        $perusahaan->coas()->create($request->all());

        return redirect()->route('mahasiswa.coa.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Import akun dari Excel.
     */
    public function import(Request $request)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new CoaImport($perusahaan->id), $request->file('file'));
            return redirect()->route('mahasiswa.coa.index')->with('success', 'Daftar akun berhasil diimport.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->route('mahasiswa.coa.index')->with('error', 'Gagal mengimport file. ' . implode(' | ', $errors));
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.coa.index')->with('error', 'Gagal mengimport file: ' . $e->getMessage());
        }
    }

    /**
     * Import COA dari template bawaan.
     */
    public function importDefault()
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $defaultCoas = \App\Models\Coa::getDefaultCoas();
        $importedCount = 0;

        foreach ($defaultCoas as $coa) {
            $exists = $perusahaan->coas()->where('kode_akun', $coa['kode_akun'])->first();
            
            if (!$exists) {
                $perusahaan->coas()->create([
                    'kode_akun' => $coa['kode_akun'],
                    'nama_akun' => $coa['nama_akun'],
                    'kode_kategori' => $coa['kode_kategori'],
                    'saldo_normal' => $coa['saldo_normal'],
                    'saldo_awal' => $coa['saldo_awal'] ?? 0,
                    'is_active' => true,
                ]);
                $importedCount++;
            }
        }

        if ($importedCount > 0) {
            return redirect()->route('mahasiswa.coa.index')->with('success', $importedCount . ' akun bawaan berhasil ditambahkan.');
        } else {
            return redirect()->route('mahasiswa.coa.index')->with('info', 'Semua akun bawaan sudah ada dalam daftar Anda.');
        }
    }
}
