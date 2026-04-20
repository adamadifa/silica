<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MahasiswaTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([
            [
                'nim' => '240101001',
                'nama' => 'Budi Santoso',
                'email' => 'budi@student.example.com',
            ],
            [
                'nim' => '240101002',
                'nama' => 'Ani Wijaya',
                'email' => 'ani@student.example.com',
            ],
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'nim',
            'nama',
            'email'
        ];
    }

    /**
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ],
        ];
    }
}
