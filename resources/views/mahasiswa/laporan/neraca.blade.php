<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Laporan Posisi Keuangan']
    ]" />

    <div class="mb-8 flex items-center justify-between no-print">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Posisi Keuangan</h1>
            <p class="text-slate-500 font-medium text-xs">Informasi mengenai aset, liabilitas, dan ekuitas perusahaan.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('mahasiswa.laporan.neraca.excel', request()->query()) }}" class="px-5 py-3 bg-emerald-100 text-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-200 transition-all flex items-center border border-emerald-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Excel
            </a>
            <a href="{{ route('mahasiswa.laporan.neraca.pdf', request()->query()) }}" class="px-5 py-3 bg-rose-100 text-rose-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-200 transition-all flex items-center border border-rose-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                PDF
            </a>
            <a href="{{ route('mahasiswa.laporan.neraca.print', request()->query()) }}" target="_blank" class="px-5 py-3 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center shadow-lg active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 00-2 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak
            </a>
        </div>
    </div>

    @php
        $totalPasiva = $total_liabilitas + $total_ekuitas;
        $isBalanced = round($total_aset, 2) == round($totalPasiva, 2);
    @endphp

    <x-report-filter :month="$month" :year="$year" />

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        <!-- Aset Column -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-10 py-8 bg-slate-900 flex items-center justify-between">
                <h3 class="text-lg font-black text-white uppercase tracking-widest">SISI AKTIVA (ASET)</h3>
                <div class="px-4 py-1.5 bg-white/10 rounded-full text-[10px] font-bold text-white uppercase tracking-wider backdrop-blur-sm">
                    Resources
                </div>
            </div>
            
            <div class="p-8 md:p-10 space-y-8">
                <!-- Aset Lancar -->
                <div>
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                        <span class="w-8 h-[1px] bg-slate-200 mr-3"></span>
                        Aset Lancar
                    </h4>
                    <div class="space-y-4">
                        @foreach($aset_lancar as $item)
                        <div class="flex items-center justify-between group">
                            <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $item->coa->nama_akun }}</span>
                            <span class="text-sm font-black text-slate-900 font-mono italic">Rp {{ number_format($item->balance, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="pt-4 flex items-center justify-between border-t border-dashed border-slate-200">
                             <span class="text-sm font-black text-slate-400 italic">Subtotal Aset Lancar</span>
                             <span class="text-md font-black text-slate-800">Rp {{ number_format($total_aset_lancar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Aset Tetap -->
                <div>
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                        <span class="w-8 h-[1px] bg-slate-200 mr-3"></span>
                        Aset Tetap
                    </h4>
                    <div class="space-y-4">
                        @foreach($aset_tetap as $item)
                        <div class="flex items-center justify-between group">
                            <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $item->coa->nama_akun }}</span>
                            <span class="text-sm font-black text-slate-900 font-mono italic">Rp {{ number_format($item->balance, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="pt-4 flex items-center justify-between border-t border-dashed border-slate-200">
                             <span class="text-sm font-black text-slate-400 italic">Subtotal Aset Tetap</span>
                             <span class="text-md font-black text-slate-800">Rp {{ number_format($total_aset_tetap, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-10 py-8 {{ $isBalanced ? 'bg-emerald-600' : 'bg-slate-900' }} border-t border-white/10 flex items-center justify-between transition-colors duration-500">
                <span class="text-sm font-black text-white uppercase tracking-[0.2em]">Total Aset</span>
                <span class="text-2xl font-black text-white font-mono">Rp {{ number_format($total_aset, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Pasiva Column -->
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-10 py-8 bg-slate-900 flex items-center justify-between">
                <h3 class="text-lg font-black text-white uppercase tracking-widest">SISI PASIVA (LIABILITAS & EKUITAS)</h3>
                <div class="px-4 py-1.5 bg-white/10 rounded-full text-[10px] font-bold text-white uppercase tracking-wider backdrop-blur-sm">
                    Claims & Ownership
                </div>
            </div>

            <div class="p-8 md:p-10 space-y-8">
                <!-- Liabilitas -->
                <div>
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                        <span class="w-8 h-[1px] bg-slate-200 mr-3"></span>
                        Kewajiban (Liabilitas)
                    </h4>
                    <div class="space-y-4">
                        @foreach($liabilitas as $item)
                        <div class="flex items-center justify-between group">
                            <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $item->coa->nama_akun }}</span>
                            <span class="text-sm font-black text-slate-900 font-mono italic">Rp {{ number_format($item->balance, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="pt-4 flex items-center justify-between border-t border-dashed border-slate-200">
                             <span class="text-sm font-black text-slate-400 italic">Subtotal Liabilitas</span>
                             <span class="text-md font-black text-slate-800">Rp {{ number_format($total_liabilitas, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Ekuitas -->
                <div>
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                        <span class="w-8 h-[1px] bg-slate-200 mr-3"></span>
                        Ekuitas (Modal)
                    </h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between group">
                            <span class="text-sm font-bold text-slate-700">Modal Akhir</span>
                            <span class="text-sm font-black text-slate-900 font-mono italic">Rp {{ number_format($total_ekuitas, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-4 flex items-center justify-between border-t border-dashed border-slate-200">
                             <span class="text-sm font-black text-slate-400 italic">Subtotal Ekuitas</span>
                             <span class="text-md font-black text-slate-800">Rp {{ number_format($total_ekuitas, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-10 py-8 {{ $isBalanced ? 'bg-emerald-600' : 'bg-rose-600' }} border-t border-white/10 flex items-center justify-between transition-colors duration-500">
                <div class="flex items-center">
                    <span class="text-sm font-black text-white uppercase tracking-[0.2em] mr-4">Total Pasiva</span>
                    @if($isBalanced)
                        <div class="px-3 py-1 bg-white/20 text-white text-[10px] font-black uppercase rounded-lg border border-white/30 backdrop-blur-md">Balanced ✅</div>
                    @else
                        <div class="px-3 py-1 bg-black/20 text-white text-[10px] font-black uppercase rounded-lg border border-white/30 backdrop-blur-md">Not Balanced ❌</div>
                    @endif
                </div>
                <span class="text-2xl font-black text-white font-mono">Rp {{ number_format($totalPasiva, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</x-app-layout>
