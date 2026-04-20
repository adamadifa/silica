<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    /**
     * Tampilkan daftar kelas milik dosen yang login.
     */
    public function index()
    {
        $query = Kelas::withCount('mahasiswas')->latest();
        
        // Admin & Superadmin dapat melihat semua kelas, Dosen hanya miliknya sendiri
        if (Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'admin') {
            $query->where('dosen_id', Auth::id());
        }
        
        $kelases = $query->get();

        return view('dosen.kelas.index', compact('kelases'));
    }

    /**
     * Form tambah kelas baru.
     */
    public function create()
    {
        $dosens = [];
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $dosens = User::where('role', 'dosen')->orderBy('name')->get();
        }

        return view('dosen.kelas.create', compact('dosens'));
    }

    /**
     * Simpan kelas baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_kelas' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:255',
        ];

        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $rules['dosen_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        Kelas::create([
            'dosen_id' => $request->dosen_id ?? Auth::id(),
            'nama_kelas' => $request->nama_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('dosen.kelas.index')->with('success', 'Kelas berhasil dibuat.');
    }

    public function show(Kelas $kela)
    {
        // Validasi kepemilikan
        if ($kela->dosen_id !== Auth::id() && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki hak akses ke kelas ini.');
        }

        // Auto-repair: Link orphan companies (kelas_id IS NULL) belonging to students in this class
        $studentsIds = $kela->mahasiswas->pluck('id');
        \App\Models\Perusahaan::whereIn('mahasiswa_id', $studentsIds)
            ->whereNull('kelas_id')
            ->update(['kelas_id' => $kela->id]);

        $kela->load(['mahasiswas.perusahaans' => function($q) use ($kela) {
            // Urutkan agar perusahaan di kelas ini muncul pertama, tapi muat semua untuk fallback
            $q->orderByRaw("CASE WHEN kelas_id = ? THEN 0 ELSE 1 END", [$kela->id]);
        }]);
        
        return view('dosen.kelas.show', compact('kela'));
    }

    /**
     * Form edit kelas.
     */
    public function edit(Kelas $kela)
    {
        if ($kela->dosen_id !== Auth::id() && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'admin') {
            return redirect()->route('dosen.kelas.index')->with('error', 'Anda tidak memiliki hak akses.');
        }

        $dosens = [];
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $dosens = User::where('role', 'dosen')->orderBy('name')->get();
        }

        return view('dosen.kelas.edit', compact('kela', 'dosens'));
    }

    /**
     * Update data kelas.
     */
    public function update(Request $request, Kelas $kela)
    {
        if ($kela->dosen_id !== Auth::id() && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'admin') {
            return redirect()->route('dosen.kelas.index')->with('error', 'Anda tidak memiliki hak akses.');
        }

        $rules = [
            'nama_kelas' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:255',
        ];

        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $rules['dosen_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        $kela->update([
            'dosen_id' => $request->dosen_id ?? $kela->dosen_id,
            'nama_kelas' => $request->nama_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('dosen.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Hapus kelas.
     */
    public function destroy(Kelas $kela)
    {
        if ($kela->dosen_id !== Auth::id() && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'admin') {
            return redirect()->route('dosen.kelas.index')->with('error', 'Anda tidak memiliki hak akses.');
        }

        $kela->delete();

        return redirect()->route('dosen.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    /**
     * Cari mahasiswa untuk didaftarkan ke kelas (AJAX/API).
     */
    public function searchMahasiswa(Request $request)
    {
        $search = $request->get('q');
        
        $query = User::where('role', 'mahasiswa')
            ->whereDoesntHave('kelasDiikuti') 
            ->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('nim_nip', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });

        $mahasiswas = $query->limit(10)->get(['id', 'name', 'nim_nip']);

        return response()->json($mahasiswas);
    }

    /**
     * Daftarkan mahasiswa ke kelas.
     */
    public function addMahasiswa(Request $request, Kelas $kela)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
        ]);

        // Cek apakah sudah terdaftar
        if ($kela->mahasiswas()->where('mahasiswa_id', $request->mahasiswa_id)->exists()) {
            return back()->with('error', 'Mahasiswa sudah terdaftar di kelas ini.');
        }

        $kela->mahasiswas()->attach($request->mahasiswa_id);

        return back()->with('success', 'Mahasiswa berhasil ditambahkan ke kelas.');
    }

    /**
     * Keluarkan mahasiswa dari kelas.
     */
    public function removeMahasiswa(Kelas $kela, User $mahasiswa)
    {
        $kela->mahasiswas()->detach($mahasiswa->id);

        return back()->with('success', 'Mahasiswa berhasil dikeluarkan dari kelas.');
    }

    /**
     * Sinkronkan studi kasus mahasiswa ke kelas ini.
     */
    public function linkPerusahaan(Kelas $kela, \App\Models\Perusahaan $perusahaan)
    {
        // Pastikan mahasiswa pemilik perusahaan ada di kelas ini
        if (!$kela->mahasiswas()->where('mahasiswa_id', $perusahaan->mahasiswa_id)->exists()) {
            return back()->with('error', 'Mahasiswa tersebut tidak terdaftar di kelas ini.');
        }

        $perusahaan->update(['kelas_id' => $kela->id]);

        return back()->with('success', 'Studi kasus berhasil disinkronkan ke kelas ini.');
    }

    /**
     * Unduh template CSV untuk import mahasiswa.
     */
    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\MahasiswaTemplateExport, 'template_import_mahasiswa.xlsx');
    }

    /**
     * Import mahasiswa dari file Excel/CSV.
     */
    public function importMahasiswa(Request $request, Kelas $kela)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $import = new \App\Imports\MahasiswaImport($kela);
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            return back()->with('success', "Import berhasil. {$import->importedCount} mahasiswa ditambahkan ke kelas. ({$import->newUsersCount} akun baru dibuat)");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
