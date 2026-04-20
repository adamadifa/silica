<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'nim_nip', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Kelas yang diampu (khusus Role Dosen)
     */
    public function kelasDiampu()
    {
        return $this->hasMany(Kelas::class, 'dosen_id');
    }

    /**
     * Kelas yang diikuti (khusus Role Mahasiswa)
     */
    public function kelasDiikuti()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa', 'mahasiswa_id', 'kelas_id');
    }

    /**
     * Studi kasus/perusahaan yang dikerjakan
     */
    public function perusahaans()
    {
        return $this->hasMany(Perusahaan::class, 'mahasiswa_id');
    }
}
