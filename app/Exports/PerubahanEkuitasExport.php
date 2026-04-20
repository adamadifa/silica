<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PerubahanEkuitasExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Perubahan Ekuitas';
    }

    public function view(): View
    {
        return view('mahasiswa.laporan.excel.perubahan-ekuitas', $this->data);
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }
}
