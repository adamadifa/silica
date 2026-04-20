<table>
    <thead>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold;">Laporan Laba Rugi</th>
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
        <!-- Penjualan Section -->
        <tr>
            <td style="font-weight: bold;">Penjualan</td>
            <td>{{ $penjualan->coa->kode_akun ?? '411' }}</td>
            <td style="text-align: right;">{{ $penjualan->balance ?? 0 }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Retur Penjualan</td>
            <td>{{ $retur_penjualan->coa->kode_akun ?? '412' }}</td>
            <td style="text-align: right;">-{{ $retur_penjualan->balance ?? 0 }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Diskon Penjualan</td>
            <td>{{ $diskon_penjualan->coa->kode_akun ?? '413' }}</td>
            <td style="text-align: right;">-{{ $diskon_penjualan->balance ?? 0 }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Penjualan Bersih</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; border-top: 1px solid #000;">{{ $penjualan_bersih }}</td>
        </tr>

        <tr><td></td><td></td><td></td></tr>
        <!-- HPP Section -->
        <tr>
            <td style="font-weight: bold;">Harga Pokok Penjualan</td>
            <td>{{ $hpp->coa->kode_akun ?? '511' }}</td>
            <td style="text-align: right;">{{ $hpp->balance ?? 0 }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Diskon Pembelian</td>
            <td>{{ $diskon_pembelian->coa->kode_akun ?? '512' }}</td>
            <td style="text-align: right;">-{{ $diskon_pembelian->balance ?? 0 }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">HPP Bersih</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; border-top: 1px solid #000;">{{ $hpp_bersih }}</td>
        </tr>

        <tr><td></td><td></td><td></td></tr>
        <!-- Laba Kotor -->
        <tr>
            <td style="font-weight: bold; text-transform: uppercase;">Laba Kotor Dari Penjualan</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; border-bottom: 3px double #000;">{{ $laba_kotor }}</td>
        </tr>

        <tr><td></td><td></td><td></td></tr>
        <!-- Beban Operasional -->
        <tr>
            <td colspan="3" style="font-weight: bold; text-decoration: underline;">Beban Operasional:</td>
        </tr>
        @foreach($beban_operasional as $item)
        <tr>
            <td style="padding-left: 20px;">{{ $item->coa->nama_akun }}</td>
            <td>{{ $item->coa->kode_akun }}</td>
            <td style="text-align: right;">{{ $item->balance }}</td>
        </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;">Total Beban</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; border-top: 1px solid #000;">{{ $total_beban }}</td>
        </tr>

        <tr><td></td><td></td><td></td></tr>
        <!-- Final Laba/Rugi -->
        <tr>
            <td style="font-weight: bold; font-size: 14pt;">Laba/Rugi Bersih</td>
            <td></td>
            <td style="text-align: right; font-weight: bold; font-size: 14pt; border-bottom: 3px double #000;">
                {{ $laba_rugi_bersih }}
            </td>
        </tr>
    </tbody>
</table>
