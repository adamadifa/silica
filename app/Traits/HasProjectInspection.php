<?php

namespace App\Traits;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait HasProjectInspection
{
    /**
     * Get the active perusahaan for the current request context.
     * Supports student owner view and Dosen/Admin inspection view.
     */
    protected function getActivePerusahaan(Request $request)
    {
        $user = Auth::user();
        $perusahaanId = $request->get('perusahaan_id');

        // Check if Dosen/Admin is inspecting a specific project
        if ($perusahaanId && in_array($user->role, ['superadmin', 'admin', 'dosen'])) {
            $perusahaan = Perusahaan::with('kelas')->findOrFail($perusahaanId);
            
            // Authorization for Dosen: Must be the class owner
            if ($user->role === 'dosen' && $perusahaan->kelas?->dosen_id !== $user->id) {
                abort(403, 'Anda tidak memiliki hak untuk memeriksa hasil mahasiswa ini.');
            }
            
            return $perusahaan;
        }

        // Default to student's own project
        return $user->perusahaans()->first();
    }

    /**
     * Check if the current user is in "Inspection Mode" (Dosen/Admin role).
     */
    protected function isInspecting(Request $request)
    {
        return $request->has('perusahaan_id') && in_array(Auth::user()->role, ['superadmin', 'admin', 'dosen']);
    }
}
