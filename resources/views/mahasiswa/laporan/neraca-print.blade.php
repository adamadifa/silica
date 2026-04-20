<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Posisi Keuangan - {{ $perusahaan->nama_perusahaan }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 15mm;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16pt;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 11pt;
            font-weight: bold;
        }

        .main-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .section {
            width: 48%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 4px 0;
            vertical-align: top;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .pl-4 {
            padding-left: 20px;
        }

        .border-top {
            border-top: 1px solid #000;
        }

        .border-double-bottom {
            border-bottom: 3px double #000;
        }

        .bg-gray {
            background-color: #f9fafb;
            padding: 5px;
        }

        @media print {
            body {
                padding: 0;
            }
            @page {
                size: A4;
                margin: 15mm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>Laporan Posisi Keuangan</h1>
        <h2>[{{ $perusahaan->nama_perusahaan }}]</h2>
        <p>Periode Berakhir: {{ date('t F Y') }}</p>
    </div>

    <div class="main-container">
        <!-- Aset Section -->
        <div class="section">
            <p class="font-bold bg-gray" style="margin-bottom: 10px;">ASET</p>
            <table>
                <tbody>
                    <tr>
                        <td colspan="3" class="font-bold">Aset Lancar</td>
                    </tr>
                    @foreach($aset_lancar as $item)
                    <tr>
                        <td class="pl-4">{{ $item->coa->nama_akun }}</td>
                        <td class="text-right">{{ number_format($item->balance, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="font-bold pl-4">Total Aset Lancar</td>
                        <td class="text-right font-bold border-top">{{ number_format($total_aset_lancar, 0, ',', '.') }}</td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <tr>
                        <td colspan="3" class="font-bold">Aset Tetap</td>
                    </tr>
                    @foreach($aset_tetap as $item)
                    <tr>
                        <td class="pl-4">{{ $item->coa->nama_akun }}</td>
                        <td class="text-right">{{ number_format($item->balance, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="font-bold pl-4">Total Aset Tetap</td>
                        <td class="text-right font-bold border-top">{{ number_format($total_aset_tetap, 0, ',', '.') }}</td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <tr class="bg-gray">
                        <td class="font-bold" style="font-size: 12pt;">TOTAL ASET</td>
                        <td class="text-right font-bold border-double-bottom" style="font-size: 12pt;">{{ number_format($total_aset, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Liabilitas & Ekuitas Section -->
        <div class="section">
            <p class="font-bold bg-gray" style="margin-bottom: 10px;">LIABILITAS & EKUITAS</p>
            <table>
                <tbody>
                    <tr>
                        <td colspan="2" class="font-bold">Liabilitas</td>
                    </tr>
                    @foreach($liabilitas as $item)
                    <tr>
                        <td class="pl-4">{{ $item->coa->nama_akun }}</td>
                        <td class="text-right">{{ number_format($item->balance, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="font-bold pl-4">Total Liabilitas</td>
                        <td class="text-right font-bold border-top">{{ number_format($total_liabilitas, 0, ',', '.') }}</td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <tr>
                        <td colspan="2" class="font-bold">Ekuitas</td>
                    </tr>
                    <tr>
                        <td class="pl-4">Modal Akhir</td>
                        <td class="text-right">{{ number_format($total_ekuitas, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold pl-4">Total Ekuitas</td>
                        <td class="text-right font-bold border-top">{{ number_format($total_ekuitas, 0, ',', '.') }}</td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <tr class="bg-gray">
                        <td class="font-bold" style="font-size: 12pt;">TOTAL LIABILITAS & EKUITAS</td>
                        <td class="text-right font-bold border-double-bottom" style="font-size: 12pt;">{{ number_format($total_liabilitas + $total_ekuitas, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
