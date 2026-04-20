<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasProjectInspection;

class DashboardController extends Controller
{
    use HasProjectInspection;

    public function index(Request $request)
    {
        $user = Auth::user();

        // Allow Dosen/Admin to inspect student dashboard
        if ($this->isInspecting($request)) {
            $perusahaan = $this->getActivePerusahaan($request);
            $perusahaan->loadCount(['coas', 'jurnals']);
            return view('mahasiswa.dashboard', compact('perusahaan'));
        }

        if ($user->role === 'mahasiswa') {
            $perusahaan = $user->perusahaans()->first();

            if (!$perusahaan) {
                return redirect()->route('mahasiswa.perusahaan.create')
                    ->with('info', 'Selamat datang di Silica! Silakan siapkan profil perusahaan praktik Anda untuk memulai.');
            }

            // Ambil statistik ringkas
            $perusahaan->loadCount(['coas', 'jurnals']);

            return view('mahasiswa.dashboard', compact('perusahaan'));
        }

        return view('dashboard');
    }
}
