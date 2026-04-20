<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasProjectInspection;
use Illuminate\Support\Facades\Storage;

class PerusahaanController extends Controller
{
    use HasProjectInspection;

    /**
     * Tampilkan daftar perusahaan/studi kasus milik mahasiswa.
     */
    public function index()
    {
        $perusahaans = Perusahaan::where('mahasiswa_id', Auth::id())
            ->latest()
            ->get();

        return view('mahasiswa.studi-kasus.index', compact('perusahaans'));
    }

    /**
     * Tampilkan detail studi kasus (workspace).
     */
    public function show(Perusahaan $perusahaan)
    {
        $user = Auth::user();

        // Authorization for Dosen/Admin inspection
        if (in_array($user->role, ['superadmin', 'admin', 'dosen'])) {
            // Validate Dosen access to specific class
            if ($user->role === 'dosen' && $perusahaan->kelas?->dosen_id !== $user->id) {
                abort(403);
            }
        } elseif ($perusahaan->mahasiswa_id !== $user->id) {
            abort(403);
        }

        $perusahaan->loadCount(['coas', 'jurnals']);

        return view('mahasiswa.studi-kasus.show', compact('perusahaan'));
    }

    /**
     * Tampilkan form pembuatan studi kasus baru.
     */
    public function create()
    {
        $kelases = Auth::user()->kelasDiikuti;
        return view('mahasiswa.studi-kasus.create', compact('kelases'));
    }

    /**
     * Simpan studi kasus baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $periodeAwal = \Carbon\Carbon::parse($request->periode_awal);

        Perusahaan::create([
            'mahasiswa_id' => Auth::id(),
            'kelas_id' => $request->kelas_id,
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'logo_path' => $logoPath,
            'periode_awal' => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
            'current_month' => $periodeAwal->month,
            'current_year' => $periodeAwal->year,
            'status_pengerjaan' => 'draft',
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil perusahaan berhasil disiapkan. Selamat bekerja!');
    }

    /**
     * Tampilkan form pengaturan perusahaan.
     */
    public function edit()
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $kelases = Auth::user()->kelasDiikuti;

        return view('mahasiswa.settings.index', compact('perusahaan', 'kelases'));
    }

    /**
     * Update profil perusahaan.
     */
    public function update(Request $request)
    {
        $perusahaan = Auth::user()->perusahaans()->first();
        if (!$perusahaan) return redirect()->route('mahasiswa.perusahaan.create');

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($perusahaan->logo_path) {
                Storage::disk('public')->delete($perusahaan->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $perusahaan->update($data);

        return redirect()->route('mahasiswa.perusahaan.edit')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}
