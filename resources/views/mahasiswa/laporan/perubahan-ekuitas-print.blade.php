<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perubahan Ekuitas - {{ $perusahaan->nama_perusahaan }}</title>
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
            padding: 10px 0;
            vertical-align: top;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .border-bottom {
            border-bottom: 1px solid #000;
        }

        .border-double-bottom {
            border-bottom: 3px double #000;
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
        <h1>Laporan Perubahan Ekuitas</h1>
        <h2>[{{ $perusahaan->nama_perusahaan }}]</h2>
        <p>Periode: {{ date('F Y') }}</p>
    </div>

    <table>
        <tbody>
            <tr>
                <td class="font-bold">Modal Awal (1 {{ date('F Y') }})</td>
                <td style="width: 100px;">{{ $modal_akun->kode_akun }}</td>
                <td class="text-right" style="width: 200px;">{{ number_format($modal_awal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Laba / Rugi Bersih</td>
                <td></td>
                <td class="text-right">{{ number_format($laba_rugi_bersih, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Prive</td>
                <td>{{ $prive_akun->kode_akun }}</td>
                <td class="text-right border-bottom">({{ number_format($v_prive, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td class="font-bold pt-4" style="font-size: 16pt;">Modal Akhir ({{ date('t F Y') }})</td>
                <td></td>
                <td class="text-right font-bold pt-4 border-double-bottom" style="font-size: 16pt;">
                    {{ number_format($modal_akhir, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
