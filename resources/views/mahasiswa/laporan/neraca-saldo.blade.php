<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Neraca Saldo']
    ]" />

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Neraca Saldo</h1>
            <p class="text-slate-500 font-medium text-xs">Ringkasan posisi keuangan dari seluruh akun untuk periode {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}.</p>
        </div>
        <x-report-filter :month="$month" :year="$year" class="mb-0" />
    </div>

    <div class="bg-white rounded-[32px] shadow-sm border border-slate-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-40">Kode Akun</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Akun</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Saldo Awal (Rp)</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Mutasi Debit (Rp)</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Mutasi Kredit (Rp)</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Saldo Akhir (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php 
                        $totalOpening = 0; 
                        $totalDebit = 0; 
                        $totalKredit = 0; 
                        $totalClosing = 0; 
                    @endphp
                    @foreach($reports as $report)
                        @php
                            $totalOpening += $report->opening;
                            $totalDebit += $report->debit;
                            $totalKredit += $report->kredit;
                            $totalClosing += $report->closing;
                        @endphp
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-8 py-4 text-xs font-bold text-blue-600">{{ $report->coa->kode_akun }}</td>
                            <td class="px-8 py-4">
                                <p class="text-sm font-bold text-slate-800">{{ $report->coa->nama_akun }}</p>
                                <p class="text-[9px] text-slate-400 uppercase font-bold tracking-tighter">{{ $report->coa->kategori }}</p>
                            </td>
                            <td class="px-8 py-4 text-right font-mono text-xs font-bold text-slate-500">
                                {{ number_format($report->opening, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-4 text-right font-mono text-xs font-bold text-slate-600">
                                {{ $report->debit > 0 ? number_format($report->debit, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-8 py-4 text-right font-mono text-xs font-bold text-slate-600">
                                {{ $report->kredit > 0 ? number_format($report->kredit, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-8 py-4 text-right font-mono text-sm font-black text-slate-900 bg-slate-50/30">
                                {{ number_format($report->closing, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-900 text-white">
                    <tr>
                        <td colspan="2" class="px-8 py-6 text-[10px] font-bold uppercase tracking-[0.2em]">Total Keseluruhan</td>
                        <td class="px-8 py-6 text-right font-mono text-sm font-bold text-slate-400 uppercase tracking-widest italic">Ringkasan</td>
                        <td class="px-8 py-6 text-right font-mono text-base font-black text-blue-400">
                            Rp {{ number_format($totalDebit, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-6 text-right font-mono text-base font-black text-indigo-400">
                            Rp {{ number_format($totalKredit, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-6 text-right font-mono text-lg font-black text-white">
                             - 
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-app-layout>
