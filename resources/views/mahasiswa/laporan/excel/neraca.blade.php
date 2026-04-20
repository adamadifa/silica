<table>
    <thead>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold;">Laporan Posisi Keuangan</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold;">{{ $perusahaan->nama_perusahaan }}</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center;">Periode Berakhir: {{ date('t F Y') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr><td></td><td></td><td></td></tr>
        <!-- Aset Section -->
        <tr>
            <td colspan="3" style="font-weight: bold; background-color: #f1f5f9;">ASET</td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight: bold; padding-left: 10px;">Aset Lancar</td>
        </tr>
        @foreach($aset_lancar as $item)
        <tr>
            <td style="padding-left: 20px;">{{ $item->coa->nama_akun }}</td>
            <td>{{ $item->coa->kode_akun }}</td>
            <td style="text-align: right;">{{ $item->balance }}</td>
        </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold; padding-left: 10px;">Total Aset Lancar</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; border-top: 1px solid #000;">{{ $total_aset_lancar }}</td>
        </tr>

        <tr><td></td><td></td><td></td></tr>
        <tr>
            <td colspan="3" style="font-weight: bold; padding-left: 10px;">Aset Tetap</td>
        </tr>
        @foreach($aset_tetap as $item)
        <tr>
            <td style="padding-left: 20px;">{{ $item->coa->nama_akun }}</td>
            <td>{{ $item->coa->kode_akun }}</td>
            <td style="text-align: right;">{{ $item->balance }}</td>
        </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold; padding-left: 10px;">Total Aset Tetap</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; border-top: 1px solid #000;">{{ $total_aset_tetap }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; font-size: 12pt;">TOTAL ASET</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; font-size: 12pt; border-bottom: 3px double #000; background-color: #f1f5f9;">
                {{ $total_aset }}
            </td>
        </tr>

        <tr><td></td><td></td><td></td></tr>
        <!-- Liabilitas Section -->
        <tr>
            <td colspan="3" style="font-weight: bold; background-color: #f1f5f9;">LIABILITAS & EKUITAS</td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight: bold; padding-left: 10px;">Liabilitas</td>
        </tr>
        @foreach($liabilitas as $item)
        <tr>
            <td style="padding-left: 20px;">{{ $item->coa->nama_akun }}</td>
            <td>{{ $item->coa->kode_akun }}</td>
            <td style="text-align: right;">{{ $item->balance }}</td>
        </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold; padding-left: 10px;">Total Liabilitas</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; border-top: 1px solid #000;">{{ $total_liabilitas }}</td>
        </tr>

        <tr><td></td><td></td><td></td></tr>
        <tr>
            <td colspan="3" style="font-weight: bold; padding-left: 10px;">Ekuitas</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Modal Akhir</td>
            <td></td>
            <td style="text-align: right;">{{ $total_ekuitas }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; padding-left: 10px;">Total Ekuitas</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; border-top: 1px solid #000;">{{ $total_ekuitas }}</td>
        </tr>

        <tr>
            <td style="font-weight: bold; font-size: 12pt;">TOTAL LIABILITAS & EKUITAS</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; font-size: 12pt; border-bottom: 3px double #000; background-color: #f1f5f9;">
                {{ $total_liabilitas + $total_ekuitas }}
            </td>
        </tr>
    </tbody>
</table>
