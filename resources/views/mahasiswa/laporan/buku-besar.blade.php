<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Buku Besar']
    ]" />

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Buku Besar</h1>
            <p class="text-slate-500 font-medium text-xs">Detail mutasi transaksi per akun untuk periode {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}.</p>
        </div>
        <div class="w-full md:w-72">
            <form action="{{ route('mahasiswa.laporan.buku-besar') }}" method="GET" id="coaFilterForm">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">
                <select name="coa_id" onchange="document.getElementById('coaFilterForm').submit()" class="w-full px-6 py-3 bg-white border-slate-200 rounded-2xl focus:border-blue-600 focus:ring-0 font-bold text-slate-700 shadow-sm transition-all cursor-pointer">
                    <option value="">Pilih Akun...</option>
                    @foreach($coas as $coa)
                        <option value="{{ $coa->id }}" {{ $selectedCoaId == $coa->id ? 'selected' : '' }}>
                            {{ $coa->kode_akun }} - {{ $coa->nama_akun }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <x-report-filter :month="$month" :year="$year" />

    @if($selectedCoa)
        <div class="bg-white rounded-[32px] shadow-sm border border-slate-50 overflow-hidden mb-10">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">{{ $selectedCoa->nama_akun }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $selectedCoa->kategori }} ({{ $selectedCoa->saldo_normal }})</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Saldo Awal</p>
                    <p class="text-sm font-black text-blue-600">Rp {{ number_format($openingBalance, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-40 text-center">Tanggal</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-40 text-center">No. Bukti</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keterangan</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right w-40">Debit</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right w-40">Kredit</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right w-40">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-medium">
                        @php $runningBalance = $openingBalance; @endphp
                        @forelse($mutations as $mutation)
                            @php
                                if ($selectedCoa->saldo_normal == 'debit') {
                                    $runningBalance += ($mutation->debit - $mutation->kredit);
                                } else {
                                    $runningBalance += ($mutation->kredit - $mutation->debit);
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-8 py-4 text-xs font-bold text-slate-400 text-center uppercase">
                                    {{ \Carbon\Carbon::parse($mutation->jurnal->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-8 py-4 text-xs font-bold text-indigo-600 text-center">
                                    {{ $mutation->jurnal->nomor_bukti }}
                                </td>
                                <td class="px-8 py-4 text-sm text-slate-600">
                                    {{ $mutation->jurnal->keterangan }}
                                </td>
                                <td class="px-8 py-4 text-right font-mono text-xs font-bold text-slate-700">
                                    {{ $mutation->debit > 0 ? number_format($mutation->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-8 py-4 text-right font-mono text-xs font-bold text-slate-700">
                                    {{ $mutation->kredit > 0 ? number_format($mutation->kredit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-8 py-4 text-right font-mono text-xs font-black text-blue-600 bg-blue-50/20">
                                    {{ number_format($runningBalance, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center text-slate-400 font-medium italic">
                                    Tidak ada mutasi transaksi untuk akun ini pada periode {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-indigo-50/50 rounded-[32px] border-2 border-dashed border-indigo-100 p-20 text-center">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-6 shadow-lg shadow-blue-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="text-lg font-black text-slate-800 mb-2">Pilih Akun Terlebih Dahulu</h3>
            <p class="text-slate-500 text-xs font-medium max-w-sm mx-auto leading-relaxed">Silakan pilih salah satu akun dari dropdown di pojok kanan atas untuk melihat detail riwayat mutasi transaksinya.</p>
        </div>
    @endif
</x-app-layout>
