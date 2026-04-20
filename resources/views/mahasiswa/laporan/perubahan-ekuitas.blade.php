<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Laporan Perubahan Ekuitas']
    ]" />

    <div class="mb-8 flex items-center justify-between no-print">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Perubahan Ekuitas</h1>
            <p class="text-slate-500 font-medium text-xs">Informasi mengenai penambahan atau pengurangan modal pemilik.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('mahasiswa.laporan.perubahan-ekuitas.excel', request()->query()) }}" class="px-5 py-3 bg-emerald-100 text-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-200 transition-all flex items-center border border-emerald-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Excel
            </a>
            <a href="{{ route('mahasiswa.laporan.perubahan-ekuitas.pdf', request()->query()) }}" class="px-5 py-3 bg-rose-100 text-rose-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-200 transition-all flex items-center border border-rose-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                PDF
            </a>
            <a href="{{ route('mahasiswa.laporan.perubahan-ekuitas.print', request()->query()) }}" target="_blank" class="px-5 py-3 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center shadow-lg active:scale-95">
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
                <h2 class="text-lg font-black text-slate-900 uppercase tracking-widest leading-tight">Laporan Perubahan Ekuitas</h2>
                <h3 class="text-2xl font-black text-slate-900 uppercase my-2">[{{ $perusahaan->nama_perusahaan }}]</h3>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-[0.2em]">Periode Berakhir: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('d F Y') }}</p>
            </div>

            <!-- Report Body -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-50">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Status Modal Awal</p>
                        <h4 class="text-lg font-bold text-slate-800 italic">Modal Awal (1 {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }})</h4>
                    </div>
                    <p class="text-xl font-black text-slate-900 font-mono">
                        Rp {{ number_format($modal_awal, 0, ',', '.') }}
                    </p>
                </div>

                <div class="space-y-4 py-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-slate-600">Laba / Rugi Bersih Tahun Berjalan</p>
                        <p class="text-sm font-black text-emerald-600 font-mono italic">
                            {{ $laba_rugi_bersih < 0 ? '-' : '+' }} Rp {{ number_format(abs($laba_rugi_bersih), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-slate-600">Prive (Pengambilan Pribadi)</p>
                        <p class="text-sm font-black text-rose-600 font-mono">
                            - Rp {{ number_format($v_prive, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Result Section -->
                <div class="mt-12 bg-slate-50/50 rounded-3xl p-8 border border-slate-100">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 mb-2 block">Ekuitas Akhir Periode</span>
                            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                                Rp {{ number_format($modal_akhir, 0, ',', '.') }}
                            </h2>
                            <div class="mt-2 w-full h-1 border-b-4 border-double border-blue-200"></div>
                        </div>
                        <div class="flex flex-col items-center md:items-end gap-2">
                             <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kondisi Modal</span>
                             <div class="px-6 py-2 rounded-xl {{ $modal_akhir > $modal_awal ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} border {{ $modal_akhir > $modal_awal ? 'border-emerald-100' : 'border-rose-100' }} text-xs font-black uppercase">
                                {{ $modal_akhir > $modal_awal ? 'Penambahan Modal' : 'Pengurangan Modal' }}
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
