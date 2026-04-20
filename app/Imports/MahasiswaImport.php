<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    protected $kelas;
    public $importedCount = 0;
    public $newUsersCount = 0;

    public function __construct(Kelas $kelas)
    {
        $kelas->load('mahasiswas');
        $this->kelas = $kelas;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!isset($row['nim']) || empty($row['nim'])) {
                continue;
            }

            $nim = trim($row['nim']);
            $name = trim($row['nama'] ?? $row['name'] ?? 'Mahasiswa');
            $email = trim($row['email'] ?? strtolower($nim) . '@student.example.com');

            // Find or create user
            $user = User::where('nim_nip', $nim)->first();

            if (!$user) {
                // Secondary check by email if NIM not found
                $user = User::where('email', $email)->first();
            }

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'nim_nip' => $nim,
                    'password' => Hash::make('password123'),
                    'role' => 'mahasiswa',
                ]);
                $this->newUsersCount++;
            }

            // Attach to class if not already attached
            if (!$this->kelas->mahasiswas->contains($user->id)) {
                $this->kelas->mahasiswas()->attach($user->id);
                $this->importedCount++;
            }
        }
    }
}
