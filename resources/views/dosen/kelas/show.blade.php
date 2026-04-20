<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Daftar Kelas', 'url' => route('dosen.kelas.index')],
        ['label' => 'Detail Kelas']
    ]" />

    <div x-data="{ 
        search: '', 
        results: [], 
        loading: false,
        importModal: false,
        fetchResults() {
            if (this.search.length < 2) {
                this.results = [];
                return;
            }
            this.loading = true;
            // Use relative path and encode query
            const url = `{{ route('dosen.mahasiswa.search', [], false) }}?q=${encodeURIComponent(this.search)}&kelas_id={{ $kela->id }}`;
            
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    this.results = Array.isArray(data) ? data : [];
                    this.loading = false;
                })
                .catch(err => {
                    console.error('Error fetching students:', err);
                    this.results = [];
                    this.loading = false;
                });
        }
    }">
        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $kela->nama_kelas }}</h1>
                <p class="text-slate-500 font-medium text-xs mt-1">Tahun Ajaran: <span class="text-blue-600 font-bold border-b border-blue-100">{{ $kela->tahun_ajaran }}</span></p>
            </div>
            
            <!-- Quick Stats -->
            <div class="flex items-center space-x-4">
                <div class="px-6 py-3 bg-white rounded-2xl border border-slate-50 shadow-sm flex items-center">
                    <span class="text-3xl font-extrabold text-slate-900 mr-2">{{ $kela->mahasiswas->count() }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Total<br>Mahasiswa</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Side: Student List -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[32px] shadow-sm border border-slate-50 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-800">Daftar Mahasiswa</h3>
                        <div class="text-sm text-slate-400 font-medium">Menampilkan {{ $kela->mahasiswas->count() }} orang</div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Mahasiswa</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">NIM</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Studi Kasus</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($kela->mahasiswas as $mhs)
                                <tr class="hover:bg-slate-50/30 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs mr-3">
                                                {{ substr($mhs->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800">{{ $mhs->name }}</p>
                                                <p class="text-[10px] text-slate-400 font-medium">{{ $mhs->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center text-sm font-bold text-slate-600">
                                        {{ $mhs->nim_nip }}
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @php $perusahaan = $mhs->perusahaans->first(); @endphp
                                        @if($perusahaan)
                                            <div class="flex flex-col items-center">
                                                <span class="text-[10px] font-bold text-slate-700 uppercase mb-1 leading-tight text-center">{{ $perusahaan->nama_perusahaan }}</span>
                                                <div class="flex items-center gap-1">
                                                    <span class="px-2 py-0.5 {{ $perusahaan->status_pengerjaan == 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} rounded text-[8px] font-black uppercase tracking-tighter">
                                                        {{ $perusahaan->status_pengerjaan ?? 'Sedang Berjalan' }}
                                                    </span>
                                                    @if($perusahaan->kelas_id != $kela->id)
                                                        <form action="{{ route('dosen.kelas.linkPerusahaan', [$kela, $perusahaan]) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="px-2 py-0.5 bg-rose-500 text-white rounded text-[8px] font-black uppercase tracking-tighter hover:bg-rose-600 transition-all shadow-sm" title="Klik untuk memindahkan studi kasus mahasiswa ini ke kelas Anda">
                                                                Sinkronkan
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-[10px] text-slate-300 font-bold uppercase italic">Belum Dibuat</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <div class="flex items-center justify-center space-x-2" x-data="{ open: false }">
                                            @if($perusahaan)
                                                <div class="relative">
                                                    <button @click="open = !open" 
                                                            class="flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-sm group whitespace-nowrap">
                                                        Periksa Hasil
                                                        <svg class="w-4 h-4 ml-2 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    </button>
                                                    
                                                    <!-- Dropdown Menu -->
                                                    <div x-show="open" 
                                                         @click.away="open = false"
                                                         x-transition:enter="transition ease-out duration-100"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 overflow-hidden text-left py-2"
                                                         style="display: none;">
                                                        
                                                        <div class="px-4 py-2 border-b border-slate-50 mb-1">
                                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ringkasan & Jurnal</p>
                                                        </div>
                                                        <a href="{{ route('dashboard', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-blue-700 hover:bg-slate-50 transition-colors">Dashboard Mahasiswa</a>
                                                        <a href="{{ route('mahasiswa.perusahaan.show', $perusahaan->id) }}?perusahaan_id={{ $perusahaan->id }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">Detail Studi Kasus</a>
                                                        <a href="{{ route('mahasiswa.jurnal.index', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">Jurnal Umum</a>
                                                        <a href="{{ route('mahasiswa.jurnal-penyesuaian.index', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">Jurnal Penyesuaian</a>
                                                        <a href="{{ route('mahasiswa.laporan.buku-besar', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">Buku Besar</a>
                                                        
                                                        <div class="px-4 py-2 border-b border-slate-50 mt-2 mb-1">
                                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Neraca & Laporan</p>
                                                        </div>
                                                        <a href="{{ route('mahasiswa.laporan.neraca-saldo', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">Neraca Saldo</a>
                                                        <a href="{{ route('mahasiswa.laporan.worksheet', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">Neraca Lajur (10 Kolom)</a>
                                                        <a href="{{ route('mahasiswa.laporan.laba-rugi', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors text-emerald-600">Laporan Laba Rugi</a>
                                                        <a href="{{ route('mahasiswa.laporan.perubahan-ekuitas', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors text-blue-600">Perubahan Ekuitas</a>
                                                        <a href="{{ route('mahasiswa.laporan.neraca', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors text-indigo-600">Posisi Keuangan</a>
                                                        
                                                        <div class="px-4 py-2 border-b border-slate-50 mt-2 mb-1">
                                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lainnya</p>
                                                        </div>
                                                        <a href="{{ route('mahasiswa.coa.index', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">Daftar Akun (COA)</a>
                                                        <a href="{{ route('mahasiswa.saldo-awal.index', ['perusahaan_id' => $perusahaan->id]) }}" class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">Saldo Awal</a>
                                                    </div>
                                                </div>
                                            @else
                                                <button disabled class="px-4 py-2 bg-slate-50 text-slate-300 rounded-xl text-xs font-black uppercase tracking-widest cursor-not-allowed">
                                                    No Data
                                                </button>
                                            @endif
 
                                            <form action="{{ route('dosen.kelas.removeMahasiswa', [$kela, $mhs]) }}" method="POST" onsubmit="return confirm('Keluarkan mahasiswa dari kelas?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-300 hover:text-red-500 rounded-xl transition-all" title="Keluarkan Dari Kelas">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-20 text-center">
                                        <div class="text-slate-300 mb-4">
                                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <p class="text-slate-400 font-medium">Belum ada mahasiswa yang terdaftar.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Side: Add Student -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-50">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xl font-bold text-slate-800">Tambah Mahasiswa</h3>
                        <button @click="importModal = true" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest flex items-center bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 transition-all active:scale-95">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path></svg>
                            Import Excel
                        </button>
                    </div>
                    <p class="text-sm text-slate-400 font-medium mb-8">Cari mahasiswa berdasarkan Nama atau NIM.</p>

                    <div class="relative">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" 
                                   x-model="search" 
                                   @input.debounce.500ms="fetchResults()"
                                   class="w-full pl-12 pr-4 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                                   placeholder="Ketik Nama / NIM...">
                        </div>

                        <!-- Search Results Dropdown -->
                        <div x-show="results.length > 0" 
                             x-transition
                             class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden max-h-60 overflow-y-auto">
                            <template x-for="user in results" :key="user.id">
                                <div class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-600 mr-2" x-text="(user.name || '').substring(0, 2)"></div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800" x-text="user.name"></p>
                                            <p class="text-[10px] text-slate-400 font-bold" x-text="user.nim_nip"></p>
                                        </div>
                                    </div>
                                    <form :action="`{{ url('/dosen/kelas') }}/${ {{ $kela->id }} }/mahasiswa`" method="POST">
                                        @csrf
                                        <input type="hidden" name="mahasiswa_id" :value="user.id">
                                        <button type="submit" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </template>
                        </div>

                        <!-- No Results -->
                        <div x-show="results.length === 0 && search.length >= 2 && !loading"
                             x-transition
                             class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 p-6 text-center">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <p class="text-sm text-slate-500 font-bold">Mahasiswa tidak ditemukan</p>
                            <p class="text-[10px] text-slate-400 font-medium mt-1">Coba gunakan Nama atau NIM yang lain.</p>
                        </div>

                        <div x-show="loading" class="mt-4 flex justify-center">
                            <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Guidance Card -->
                <div class="bg-blue-600 rounded-[32px] p-8 text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold mb-3">Mulai Studi Kasus</h3>
                        <p class="text-blue-100 text-sm leading-relaxed mb-6">Mahasiswa yang sudah terdaftar dapat mulai mengerjakan pencatatan akuntansi sesuai perusahaan yang Anda tugaskan.</p>
                        <button class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-xl text-xs font-bold hover:bg-blue-50 transition-colors shadow-lg">
                            Kelola Studi Kasus
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                    <!-- Abstract Background Overlay -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
                </div>
            </div>
        </div>
    <!-- Import Modal -->
    <div x-show="importModal" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <div x-show="importModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" 
                 @click="importModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="importModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[32px] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-10 border border-white/20">
                
                <div class="mb-8 text-center">
                    <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-[24px] flex items-center justify-center mx-auto mb-6 rotate-3 group">
                        <svg class="w-10 h-10 -rotate-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Import Mahasiswa</h3>
                    <p class="text-sm text-slate-500 font-medium">Unggah file Excel (.xlsx) atau CSV berisi daftar mahasiswa.</p>
                </div>

                <form action="{{ route('dosen.kelas.import', $kela) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div class="relative group">
                            <label class="block mb-6 cursor-pointer">
                                <span class="sr-only">Choose file</span>
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-3 file:px-6
                                    file:rounded-2xl file:border-0
                                    file:text-xs file:font-black file:uppercase file:tracking-widest
                                    file:bg-blue-50 file:text-blue-600
                                    hover:file:bg-blue-100
                                    cursor-pointer border border-slate-100 p-4 rounded-3xl bg-slate-50/30 group-hover:border-blue-200 transition-all"
                                />
                            </label>
                        </div>

                        <div class="bg-amber-50 rounded-2xl p-4 border border-amber-100">
                            <div class="flex">
                                <svg class="h-5 w-5 text-amber-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="ml-3">
                                    <h4 class="text-xs font-black text-amber-800 uppercase tracking-wider mb-1">Penting: Format Kolom</h4>
                                    <p class="text-[10px] text-amber-700 font-medium leading-relaxed">Pastikan file memiliki tajuk (header) di baris pertama dengan kolom: <span class="font-bold underline">nim</span>, <span class="font-bold underline">nama</span>, dan <span class="font-bold underline">email</span>.</p>
                                    <a href="{{ route('dosen.kelas.import-template') }}" class="inline-flex items-center mt-2 text-[10px] font-black text-blue-900 bg-blue-100 px-2 py-1 rounded-md hover:bg-blue-200 transition-all shadow-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Unduh Template Excel (.xlsx)
                                    </a>
                                    <p class="text-[10px] text-amber-700 font-medium mt-1">Password default untuk akun baru adalah: <span class="font-bold italic">password123</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <button type="submit" 
                                    class="flex-1 px-8 py-4 bg-blue-600 text-white text-sm font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-0.5 transition-all active:scale-95 text-center">
                                Mulai Import
                            </button>
                            <button type="button" 
                                    @click="importModal = false"
                                    class="flex-1 px-8 py-4 bg-white text-slate-400 text-sm font-black uppercase tracking-widest rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all text-center">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
