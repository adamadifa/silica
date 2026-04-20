@props(['perusahaan'])

@if(request()->has('perusahaan_id') && in_array(auth()->user()->role, ['superadmin', 'admin', 'dosen']))
    <div class="mb-8 p-6 bg-gradient-to-r from-amber-500 to-orange-600 rounded-[32px] text-white shadow-xl shadow-amber-100 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative group">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
        
        <div class="flex items-center relative z-10">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-6 backdrop-blur-md">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </div>
            <div>
                <h4 class="text-xl font-black tracking-tight leading-none mb-2 underline decoration-white/30 underline-offset-4 uppercase">Mode Pemeriksaan</h4>
                <p class="text-amber-50 font-bold text-sm tracking-wide">
                    @if(isset($perusahaan->mahasiswa))
                        Sedang melihat: <span class="text-white">{{ $perusahaan->mahasiswa->name }}</span> 
                        <span class="mx-2 opacity-30">|</span> 
                    @endif
                    Project: <span class="text-white">{{ $perusahaan->nama_perusahaan }}</span>
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 relative z-10">
            @if(isset($perusahaan->kelas_id))
            <a href="{{ route('dosen.kelas.show', $perusahaan->kelas_id) }}" 
               class="px-8 py-3 bg-white text-orange-600 font-extrabold rounded-2xl hover:bg-orange-50 transition-all shadow-sm text-sm uppercase tracking-widest flex items-center group/btn active:scale-95">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Kelas
            </a>
            @endif
        </div>
    </div>
@endif
