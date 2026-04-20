<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Dashboard']
    ]" />

    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div class="flex items-start space-x-6">
            <div class="w-20 h-20 bg-white rounded-3xl shadow-sm border border-slate-50 overflow-hidden flex-shrink-0">
                <img src="{{ $perusahaan->logo_url }}" alt="Logo" class="w-full h-full object-cover">
            </div>
            <div>
                <div class="flex items-center space-x-2 text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M7 14h.01" /><path d="M10 14h.01" /><path d="M13 14h.01" /><path d="M16 14h.01" /><path d="M7 17h.01" /><path d="M10 17h.01" /><path d="M13 17h.01" /><path d="M16 17h.01" /></svg>
                        Bulan Berjalan: {{ \Carbon\Carbon::createFromDate($perusahaan->current_year, $perusahaan->current_month, 1)->format('F Y') }}
                    </span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $perusahaan->nama_perusahaan }}</h1>
                <p class="text-slate-500 font-medium text-xs mt-1 italic">
                    {{ $perusahaan->email ?? 'Silakan lengkapi profil perusahaan' }}
                </p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <form action="{{ route('mahasiswa.perusahaan.closing') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-indigo-600 rounded-2xl text-sm font-bold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Tutup Buku {{ \Carbon\Carbon::createFromDate($perusahaan->current_year, $perusahaan->current_month, 1)->format('M Y') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- COA Card -->
        <a href="{{ route('mahasiswa.coa.index') }}" class="bg-white p-8 rounded-[32px] border border-slate-50 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Bagan Akun</h3>
            <p class="text-xs text-slate-400 font-medium">Susun Master Akun (COA)</p>
        </a>

        <!-- Jurnal Card -->
        <a href="{{ route('mahasiswa.jurnal.index') }}" class="bg-white p-8 rounded-[32px] border border-slate-50 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Jurnal Umum</h3>
            <p class="text-xs text-slate-400 font-medium">Catat Transaksi Harian</p>
        </a>

        <!-- Buku Besar Card -->
        <a href="#" class="bg-white p-8 rounded-[32px] border border-slate-50 shadow-sm opacity-60 cursor-not-allowed">
            <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-6 font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Buku Besar</h3>
            <p class="text-xs text-slate-400 font-medium italic">Otomatis Terposting</p>
        </a>

        <!-- Laporan Card -->
        <a href="#" class="bg-white p-8 rounded-[32px] border border-slate-50 shadow-sm opacity-60 cursor-not-allowed">
            <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-6 font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m-8 13h12a2 2 0 002-2V5a2 2 0 00-2-2H4a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Laporan Keuangan</h3>
            <p class="text-xs text-slate-400 font-medium italic">Neraca & Laba Rugi</p>
        </a>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 bg-white rounded-[40px] p-10 border border-slate-50 shadow-sm">
            <h3 class="text-xl font-extrabold text-slate-800 mb-6">Ringkasan Aktivitas</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-slate-50 rounded-3xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Akun (COA)</p>
                    <p class="text-3xl font-black text-slate-800">{{ $perusahaan->coas_count ?? 0 }}</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-3xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Jurnal</p>
                    <p class="text-3xl font-black text-slate-800">{{ $perusahaan->jurnals_count ?? 0 }}</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-3xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status Balance</p>
                    <div class="flex items-center mt-1">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        <p class="text-xl font-extrabold text-slate-800 leading-none">OK</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 bg-blue-600 rounded-[40px] p-10 text-white flex flex-col justify-between shadow-xl shadow-blue-200">
            <div>
                <h3 class="text-xl font-bold mb-8">Informasi Entitas</h3>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="p-2 bg-white/10 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-1">Alamat Kantor</p>
                            <p class="text-xs font-medium leading-relaxed">{{ $perusahaan->alamat ?? 'Belum diatur' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="p-2 bg-white/10 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-1">Telepon</p>
                            <p class="text-xs font-medium">{{ $perusahaan->telepon ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="p-2 bg-white/10 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-1">Email</p>
                            <p class="text-xs font-medium truncate w-40">{{ $perusahaan->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-10 pt-6 border-t border-white/10">
                <a href="{{ route('mahasiswa.perusahaan.edit') }}" class="block w-full py-4 bg-white/10 hover:bg-white text-blue-600 hover:text-blue-600 bg-transparent hover:bg-white border border-white/20 rounded-2xl text-center text-xs font-extrabold transition-all">
                    Edit Profil Perusahaan
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
