<?php

namespace App\Imports;

use App\Models\Coa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class CoaImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $perusahaan_id;

    public function __construct($perusahaan_id)
    {
        $this->perusahaan_id = $perusahaan_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Check for duplicates within the same company
        $existing = Coa::where('perusahaan_id', $this->perusahaan_id)
            ->where('kode_akun', $row['kode_akun'])
            ->first();

        if ($existing) {
            return null; // Skip if exists
        }

        // Cari kategori berdasarkan nama atau kode
        $kategori = \App\Models\KategoriCoa::where('nama_kategori', 'like', '%' . $row['kategori'] . '%')
            ->orWhere('kode_kategori', $row['kategori'])
            ->first();

        // Jika kategori tidak ditemukan, berikan default atau batalkan (di sini kita gunakan default atau biarkan gagal jika strict)
        $kodeKategori = $kategori ? $kategori->kode_kategori : 'L'; // 'L' is Liabilitas just as a fallback, but validation should catch it before.

        return new Coa([
            'perusahaan_id' => $this->perusahaan_id,
            'kode_akun'     => $row['kode_akun'],
            'nama_akun'     => $row['nama_akun'],
            'kode_kategori' => $kodeKategori,
            'saldo_normal'  => strtolower($row['saldo_normal']),
            'saldo_awal'    => $row['saldo_awal'] ?? 0,
            'is_active'     => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'kategori' => 'required',
            'saldo_normal' => ['required', Rule::in(['debit', 'kredit', 'DEBIT', 'KREDIT', 'Debit', 'Kredit'])],
        ];
    }
    
    /**
     * Custom error messages for validation
     */
    public function customValidationMessages()
    {
        return [
            'kode_akun.required' => 'Kolom kode_akun wajib diisi.',
            'nama_akun.required' => 'Kolom nama_akun wajib diisi.',
            'kategori.required' => 'Kolom kategori wajib diisi.',
            'saldo_normal.required' => 'Kolom saldo_normal wajib diisi.',
            'saldo_normal.in' => 'Saldo normal harus "debit" atau "kredit".',
        ];
    }
}
