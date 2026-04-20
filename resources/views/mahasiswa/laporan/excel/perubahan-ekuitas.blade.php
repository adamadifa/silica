<table>
    <thead>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold;">Laporan Perubahan Ekuitas</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold;">{{ $perusahaan->nama_perusahaan }}</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center;">Periode: {{ date('F Y') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr><td></td><td></td><td></td></tr>
        <tr>
            <td style="font-weight: bold;">Modal Awal (1 {{ date('F Y') }})</td>
            <td>{{ $modal_akun->kode_akun }}</td>
            <td style="text-align: right;">{{ $modal_awal }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Laba / Rugi Bersih</td>
            <td></td>
            <td style="text-align: right;">{{ $laba_rugi_bersih }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Prive</td>
            <td>{{ $prive_akun->kode_akun }}</td>
            <td style="text-align: right;">-{{ $v_prive }}</td>
        </tr>
        <tr><td></td><td></td><td></td></tr>
        <tr>
            <td style="font-weight: bold; font-size: 14pt;">Modal Akhir ({{ date('t F Y') }})</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; font-size: 14pt; border-bottom: 3px double #000;">
                {{ $modal_akhir }}
            </td>
        </tr>
    </tbody>
</table>
