<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Laporan Laba Rugi']
    ]" />

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-family: 'Times New Roman', serif; }
            .print-container { width: 100% !important; margin: 0 !important; padding: 0 !important; border: none !important; shadow: none !important; }
            .report-header { text-align: center; margin-bottom: 30px; }
            .formal-table { width: 100%; border-collapse: collapse; }
            .formal-table th, .formal-table td { padding: 8px 12px; border: none; }
            .border-bottom { border-bottom: 1px solid black !important; }
            .border-double-bottom { border-bottom: 3px double black !important; }
            .font-bold { font-weight: bold !important; }
            .text-right { text-align: right !important; }
            .pl-8 { padding-left: 2rem !important; }
            .max-w-4xl { max-width: 100% !important; }
            .bg-white, .shadow-xl, .border, .rounded-\[40px\] { border: none !important; shadow: none !important; border-radius: 0 !important; }
        }
    </style>

    <div class="mb-8 flex items-center justify-between no-print">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Laba Rugi</h1>
            <p class="text-slate-500 font-medium text-xs">Performa finansial perusahaan berdasarkan pendapatan dan beban.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('mahasiswa.laporan.laba-rugi.excel', request()->query()) }}" class="px-5 py-3 bg-emerald-100 text-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-200 transition-all flex items-center border border-emerald-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Excel
            </a>
            <a href="{{ route('mahasiswa.laporan.laba-rugi.pdf', request()->query()) }}" class="px-5 py-3 bg-rose-100 text-rose-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-200 transition-all flex items-center border border-rose-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                PDF
            </a>
            <a href="{{ route('mahasiswa.laporan.laba-rugi.print', request()->query()) }}" target="_blank" class="px-5 py-3 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center shadow-lg active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 00-2 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto print-container">
        <x-report-filter :month="$month" :year="$year" />
        
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden p-10 md:p-16">
            <!-- Report Header -->
            <div class="text-center mb-12 border-b border-slate-50 pb-8">
                <h2 class="text-lg font-black text-slate-900 uppercase tracking-widest leading-tight">Laporan Laba Rugi</h2>
                <h3 class="text-2xl font-black text-slate-900 uppercase my-2">[{{ $perusahaan->nama_perusahaan }}]</h3>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-[0.2em]">Periode: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</p>
            </div>

            <!-- Formal Report Body -->
            <table class="w-full text-sm">
                <tbody>
                    <!-- Pendapatan Section -->
                    <tr>
                        <td class="py-2 font-bold text-slate-800">Penjualan</td>
                        <td class="py-2 text-slate-400">{{ $penjualan->coa->kode_akun ?? '411' }}</td>
                        <td class="py-2 text-right font-mono">{{ number_format($penjualan->balance ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 font-bold text-slate-800">Retur Penjualan</td>
                        <td class="py-2 text-slate-400">{{ $retur_penjualan->coa->kode_akun ?? '412' }}</td>
                        <td class="py-2 text-right font-mono">({{ number_format($retur_penjualan->balance ?? 0, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <td class="py-2 font-bold text-slate-800">Diskon Penjualan</td>
                        <td class="py-2 text-slate-400">{{ $diskon_penjualan->coa->kode_akun ?? '413' }}</td>
                        <td class="py-2 text-right font-mono border-bottom">({{ number_format($diskon_penjualan->balance ?? 0, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <td class="py-4 font-black text-slate-900 text-base">Penjualan Bersih</td>
                        <td></td>
                        <td class="py-4 text-right font-black text-slate-900 text-base font-mono border-bottom">
                            {{ number_format($penjualan_bersih, 0, ',', '.') }}
                        </td>
                    </tr>

                    <!-- HPP Section -->
                    <tr>
                        <td class="py-2 pt-6 font-bold text-slate-800">Harga Pokok Penjualan</td>
                        <td class="py-2 pt-6 text-slate-400">{{ $hpp->coa->kode_akun ?? '511' }}</td>
                        <td class="py-2 pt-6 text-right font-mono">{{ number_format($hpp->balance ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 font-bold text-slate-800">Diskon Pembelian</td>
                        <td class="py-2 text-slate-400">{{ $diskon_pembelian->coa->kode_akun ?? '512' }}</td>
                        <td class="py-2 text-right font-mono border-bottom">({{ number_format($diskon_pembelian->balance ?? 0, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <td class="py-4 font-black text-slate-900 text-base">HPP Bersih</td>
                        <td></td>
                        <td class="py-4 text-right font-black text-slate-900 text-base font-mono border-bottom">
                            {{ number_format($hpp_bersih, 0, ',', '.') }}
                        </td>
                    </tr>

                    <!-- Laba Kotor -->
                    <tr>
                        <td class="py-6 font-black text-slate-900 text-lg uppercase tracking-tight">Laba Kotor Dari Penjualan</td>
                        <td></td>
                        <td class="py-6 text-right font-black text-slate-900 text-lg font-mono border-double-bottom">
                            {{ number_format($laba_kotor, 0, ',', '.') }}
                        </td>
                    </tr>

                    <!-- Beban Operasional -->
                    <tr><td colspan="3" class="pt-8 pb-4 font-black text-slate-400 text-[10px] uppercase tracking-[0.3em]">Beban Operasional</td></tr>
                    @foreach($beban_operasional as $item)
                    <tr>
                        <td class="py-2 font-bold text-slate-700 pl-4">{{ $item->coa->nama_akun }}</td>
                        <td class="py-2 text-slate-400">{{ $item->coa->kode_akun }}</td>
                        <td class="py-2 text-right font-mono">{{ number_format($item->balance, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="py-6 font-black text-slate-900 text-base uppercase">Total Beban</td>
                        <td></td>
                        <td class="py-6 text-right font-black text-slate-900 text-base font-mono border-bottom">
                            {{ number_format($total_beban, 0, ',', '.') }}
                        </td>
                    </tr>

                    <!-- Final Laba/Rugi -->
                    <tr>
                        <td class="py-8 font-black text-slate-900 text-2xl uppercase tracking-tighter">Laba/Rugi Bersih</td>
                        <td></td>
                        <td class="py-8 text-right font-black text-slate-900 text-2xl font-mono {{ $laba_rugi_bersih < 0 ? 'text-rose-600' : 'text-emerald-600' }} border-double-bottom">
                            {{ $laba_rugi_bersih < 0 ? '-' : '' }}{{ number_format(abs($laba_rugi_bersih), 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
