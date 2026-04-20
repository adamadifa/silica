<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LabaRugiExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Laporan Laba Rugi';
    }

    public function view(): View
    {
        return view('mahasiswa.laporan.excel.laba-rugi', $this->data);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the headers and totals if needed, 
            // but the Table from view usually handles most.
        ];
    }
}
