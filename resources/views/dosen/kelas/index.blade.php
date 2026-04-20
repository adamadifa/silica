<x-app-layout>
    <x-breadcrumbs :links="[['label' => 'Daftar Kelas']]" />

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Daftar Kelas Anda</h1>
            <p class="text-slate-500 font-medium text-[11px]">Kelola mahasiswa dan pantau progres pengerjaan tugas di setiap kelas.</p>
        </div>
        <div>
            <a href="{{ route('dosen.kelas.create') }}" class="flex items-center px-6 py-3 bg-blue-600 rounded-2xl text-sm font-bold text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Buat Kelas Baru
            </a>
        </div>
    </div>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($kelases as $kelas)
            <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-slate-50 hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-extrabold uppercase tracking-widest">{{ $kelas->tahun_ajaran }}</span>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 mb-2 truncate group-hover:text-blue-600 transition-colors">{{ $kelas->nama_kelas }}</h3>
                    
                    <div class="flex flex-col space-y-2 mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center text-sm text-slate-400 font-medium">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                {{ $kelas->mahasiswas_count }} Mahasiswa
                            </div>
                            <div class="flex items-center text-sm text-slate-400 font-medium">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                {{ $kelas->perusahaans_count ?? 0 }} Studi Kasus
                            </div>
                        </div>
                        @if(Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin')
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pt-2 border-t border-slate-50 italic">Dosen: {{ $kelas->dosen->name }}</p>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('dosen.kelas.show', $kelas) }}" class="flex-1 flex items-center justify-center py-3.5 bg-slate-50 rounded-2xl text-sm font-bold text-slate-700 hover:bg-blue-600 hover:text-white transition-all">
                            Buka Kelas
                        </a>
                        <a href="{{ route('dosen.kelas.edit', $kelas) }}" class="p-3.5 bg-slate-50 rounded-2xl text-slate-400 hover:bg-slate-900 hover:text-white transition-all group/edit" title="Edit Kelas">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('dosen.kelas.destroy', $kelas) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-3.5 bg-slate-50 rounded-2xl text-slate-400 hover:bg-rose-600 hover:text-white transition-all" title="Hapus Kelas">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white rounded-[32px] border-2 border-dashed border-slate-100 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada kelas</h3>
                <p class="text-slate-400 max-w-sm mb-8">Anda belum memiliki kelas yang diampu. Silakan buat kelas baru untuk memulai.</p>
                <a href="{{ route('dosen.kelas.create') }}" class="text-blue-600 font-bold hover:underline">Sama-sama buat kelas?</a>
            </div>
        @endforelse
    </div>
</x-app-layout>
