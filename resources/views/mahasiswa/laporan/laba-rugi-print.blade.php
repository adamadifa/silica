<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba Rugi - {{ $perusahaan->nama_perusahaan }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20mm;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 18pt;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 5px 0;
            vertical-align: top;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .pl-8 {
            padding-left: 40px;
        }

        .border-bottom {
            border-bottom: 1px solid #000;
        }

        .border-double-bottom {
            border-bottom: 3px double #000;
        }

        .pt-4 {
            padding-top: 20px;
        }

        .pb-2 {
            padding-bottom: 10px;
        }

        @media print {
            body {
                padding: 0;
            }
            @page {
                size: A4;
                margin: 20mm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>Laporan Laba Rugi</h1>
        <h2>[{{ $perusahaan->nama_perusahaan }}]</h2>
        <p>Periode: {{ date('F Y') }}</p>
    </div>

    <table>
        <tbody>
            <!-- Penjualan Section -->
            <tr>
                <td class="font-bold">Penjualan</td>
                <td style="width: 100px;">{{ $penjualan->coa->kode_akun ?? '411' }}</td>
                <td class="text-right" style="width: 200px;">{{ number_format($penjualan->balance ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Retur Penjualan</td>
                <td>{{ $retur_penjualan->coa->kode_akun ?? '412' }}</td>
                <td class="text-right">({{ number_format($retur_penjualan->balance ?? 0, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td class="font-bold">Diskon Penjualan</td>
                <td>{{ $diskon_penjualan->coa->kode_akun ?? '413' }}</td>
                <td class="text-right border-bottom">({{ number_format($diskon_penjualan->balance ?? 0, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td class="font-bold pt-4" style="font-size: 13pt;">Penjualan Bersih</td>
                <td class="pt-4"></td>
                <td class="text-right font-bold pt-4 border-bottom" style="font-size: 13pt;">{{ number_format($penjualan_bersih, 0, ',', '.') }}</td>
            </tr>

            <!-- HPP Section -->
            <tr>
                <td class="font-bold pt-4">Harga Pokok Penjualan</td>
                <td class="pt-4">{{ $hpp->coa->kode_akun ?? '511' }}</td>
                <td class="text-right pt-4">{{ number_format($hpp->balance ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Diskon Pembelian</td>
                <td>{{ $diskon_pembelian->coa->kode_akun ?? '512' }}</td>
                <td class="text-right border-bottom">({{ number_format($diskon_pembelian->balance ?? 0, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td class="font-bold pt-4" style="font-size: 13pt;">HPP Bersih</td>
                <td class="pt-4"></td>
                <td class="text-right font-bold pt-4 border-bottom" style="font-size: 13pt;">{{ number_format($hpp_bersih, 0, ',', '.') }}</td>
            </tr>

            <!-- Laba Kotor -->
            <tr>
                <td class="font-bold pt-4 pb-2" style="font-size: 14pt;">Laba Kotor Dari Penjualan</td>
                <td class="pt-4 pb-2"></td>
                <td class="text-right font-bold pt-4 pb-2 border-double-bottom" style="font-size: 14pt;">{{ number_format($laba_kotor, 0, ',', '.') }}</td>
            </tr>

            <!-- Beban Operasional -->
            <tr>
                <td colspan="3" class="font-bold pt-4 pb-2" style="text-decoration: underline;">Beban Operasional:</td>
            </tr>
            @foreach($beban_operasional as $item)
            <tr>
                <td class="pl-8">{{ $item->coa->nama_akun }}</td>
                <td>{{ $item->coa->kode_akun }}</td>
                <td class="text-right">{{ number_format($item->balance, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td class="font-bold pt-4" style="font-size: 13pt;">Total Beban</td>
                <td class="pt-4"></td>
                <td class="text-right font-bold pt-4 border-bottom" style="font-size: 13pt;">{{ number_format($total_beban, 0, ',', '.') }}</td>
            </tr>

            <!-- Final Laba/Rugi -->
            <tr>
                <td class="font-bold pt-4" style="font-size: 16pt;">Laba/Rugi Bersih</td>
                <td class="pt-4"></td>
                <td class="text-right font-bold pt-4 border-double-bottom" style="font-size: 16pt;">
                    {{ $laba_rugi_bersih < 0 ? '-' : '' }}{{ number_format(abs($laba_rugi_bersih), 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
