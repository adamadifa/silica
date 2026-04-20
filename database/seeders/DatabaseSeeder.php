<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KategoriCoaSeeder::class,
        ]);

        // 0. Buat Super Admin
        User::create([
            'name' => 'Super Admin Silica',
            'email' => 'superadmin@example.com',
            'nim_nip' => '00000000',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // 0.1 Buat Admin
        User::create([
            'name' => 'Admin Silica',
            'email' => 'admin@example.com',
            'nim_nip' => '11111111',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 1. Buat User Dosen
        $dosen = User::create([
            'name' => 'Dosen Akuntansi',
            'email' => 'dosen@example.com',
            'nim_nip' => '12345678',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        // 2. Buat User Mahasiswa
        $mhs1 = User::create([
            'name' => 'Mahasiswa 1',
            'email' => 'mhs1@example.com',
            'nim_nip' => '87654321',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $mhs2 = User::create([
            'name' => 'Mahasiswa 2',
            'email' => 'mhs2@example.com',
            'nim_nip' => '87654322',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        // 3. Buat Kelas
        $kelas = Kelas::create([
            'dosen_id' => $dosen->id,
            'nama_kelas' => 'Pengantar Akuntansi A',
            'tahun_ajaran' => '2024/2025 (Ganjil)',
        ]);

        // 4. Daftarkan Mahasiswa ke Kelas
        $kelas->mahasiswas()->attach([$mhs1->id, $mhs2->id]);
    }
}
