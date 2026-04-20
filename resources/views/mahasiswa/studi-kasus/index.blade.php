<x-app-layout>
    <x-breadcrumbs :links="[['label' => 'Studi Kasus Saya']]" />

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Studi Kasus Saya</h1>
            <p class="text-slate-500 font-medium text-xs">Pilih perusahaan untuk mulai mencatat transaksi akuntansi.</p>
        </div>
        <div>
            <a href="{{ route('mahasiswa.perusahaan.create') }}" class="flex items-center px-6 py-3 bg-blue-600 rounded-2xl text-sm font-bold text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Buat Studi Kasus
            </a>
        </div>
    </div>

    <!-- Perusahaan Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($perusahaans as $perusahaan)
            <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-slate-50 hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="px-3 py-1 {{ $perusahaan->status_pengerjaan == 'selesai' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }} rounded-lg text-[10px] font-extrabold uppercase tracking-widest">
                            {{ $perusahaan->status_pengerjaan }}
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 mb-2 truncate group-hover:text-blue-600 transition-colors">{{ $perusahaan->nama_perusahaan }}</h3>
                    <p class="text-slate-400 text-xs font-medium mb-8">
                        Periode: {{ \Carbon\Carbon::parse($perusahaan->periode_awal)->format('M Y') }} - {{ \Carbon\Carbon::parse($perusahaan->periode_akhir)->format('M Y') }}
                    </p>

                    <a href="{{ route('mahasiswa.perusahaan.show', $perusahaan) }}" class="flex items-center justify-center w-full py-3.5 bg-slate-50 rounded-2xl text-sm font-bold text-slate-700 hover:bg-blue-600 hover:text-white transition-all">
                        Masuk Ke Workspace
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white rounded-[32px] border-2 border-dashed border-slate-100 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada studi kasus</h3>
                <p class="text-slate-400 max-w-sm mb-8">Silakan buat studi kasus baru (perusahaan) untuk mulai mempraktikkan akuntansi dasar.</p>
                <a href="{{ route('mahasiswa.perusahaan.create') }}" class="text-blue-600 font-bold hover:underline">Buat Sekarang?</a>
            </div>
        @endforelse
    </div>
</x-app-layout>
