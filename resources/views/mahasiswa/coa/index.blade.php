<x-app-layout>
    <div x-data="{ showImportModal: false }">
        <x-breadcrumbs :links="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Bagan Akun (COA)']
        ]" />

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 max-w-5xl">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Bagan Akun (COA)</h1>
                <p class="text-slate-500 font-medium text-xs">Daftar semua akun yang digunakan untuk pencatatan transaksi.</p>
            </div>
            @if(!request()->has('perusahaan_id'))
            <div class="flex items-center space-x-3">
                <form action="{{ route('mahasiswa.coa.import-default') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all shadow-sm group">
                        <svg class="w-5 h-5 mr-2 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Gunakan Akun
                    </button>
                </form>
                <button @click="showImportModal = true" class="flex items-center px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all shadow-sm group">
                    <svg class="w-5 h-5 mr-2 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    Import
                </button>
                <a href="{{ route('mahasiswa.coa.create') }}" class="flex items-center px-6 py-3 bg-blue-600 rounded-2xl text-sm font-bold text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 group">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah
                </a>
            </div>
            @endif
        </div>

        <!-- COA Table -->
        <div class="max-w-5xl">
            <div class="bg-white rounded-[32px] shadow-sm border border-slate-50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-4 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap w-32">Kode Akun</th>
                            <th class="px-4 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Akun</th>
                            <th class="px-4 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori</th>
                            <th class="px-4 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Saldo Normal</th>
                            @if(!request()->has('perusahaan_id'))
                            <th class="px-4 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($coas as $coa)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-4 py-2 text-sm font-bold text-blue-600">
                                {{ $coa->kode_akun }}
                            </td>
                            <td class="px-4 py-2">
                                <p class="text-sm font-bold text-slate-800">{{ $coa->nama_akun }}</p>
                            </td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded text-[9px] font-bold uppercase tracking-wider">
                                    {{ $coa->kategoriCoa?->nama_kategori ?? $coa->kode_kategori }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest {{ $coa->saldo_normal == 'debit' ? 'text-blue-500' : 'text-indigo-500' }}">
                                    {{ $coa->saldo_normal }}
                                </span>
                            </td>
                            @if(!request()->has('perusahaan_id'))
                            <td class="px-4 py-2 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <button class="p-1.5 text-slate-300 hover:text-blue-500 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button class="p-1.5 text-slate-300 hover:text-red-500 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="text-slate-300 mb-4">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <p class="text-slate-400 font-medium">Belum ada akun yang terdaftar.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
        </div>

        <!-- Import Modal -->
        <div x-show="showImportModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <div class="bg-white rounded-[32px] shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in-up"
                 @click.away="showImportModal = false">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-extrabold text-slate-900">Import Bagan Akun</h3>
                        <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="mb-8 p-6 bg-blue-50 rounded-2xl border border-blue-100">
                        <h4 class="text-sm font-bold text-blue-900 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            Instruksi Format Excel
                        </h4>
                        <p class="text-xs text-blue-700 leading-relaxed mb-4">
                            Pastikan file Excel Anda memiliki baris judul (header) dengan urutan kolom sebagai berikut:
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-white/60 p-2 rounded-lg text-[10px] font-bold text-blue-800 border border-blue-100">1. kode_akun</div>
                            <div class="bg-white/60 p-2 rounded-lg text-[10px] font-bold text-blue-800 border border-blue-100">2. nama_akun</div>
                            <div class="bg-white/60 p-2 rounded-lg text-[10px] font-bold text-blue-800 border border-blue-100">3. kategori</div>
                            <div class="bg-white/60 p-2 rounded-lg text-[10px] font-bold text-blue-800 border border-blue-100">4. saldo_normal</div>
                        </div>
                        <p class="text-[10px] text-blue-500 mt-4 italic">
                            * Kategori: Aset Lancar, Aset Tetap, Liabilitas, Ekuitas, Pendapatan, Beban
                            <br>
                            * Saldo Normal: debit atau kredit
                        </p>
                        <div class="mt-4 pt-4 border-t border-blue-100 flex items-center justify-between">
                            <span class="text-xs text-blue-800 font-semibold">Butuh template?</span>
                            <a href="{{ asset('templates/coa_template.csv') }}" download class="flex items-center px-4 py-2 bg-white rounded-xl text-xs font-bold text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-200 transition-all shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unduh Template CSV
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('mahasiswa.coa.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-8">
                            <label for="file" class="block text-sm font-semibold text-slate-700 mb-4">Pilih File Excel (.xlsx, .xls, .csv)</label>
                            <div class="relative group">
                                <input type="file" name="file" id="file" required
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full px-6 py-10 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl group-hover:bg-slate-100 group-hover:border-blue-300 transition-all text-center">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-4 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-sm font-bold text-slate-600">Klik untuk pilih file atau seret file ke sini</p>
                                    <p class="text-xs text-slate-400 mt-2" id="file-name-display">Belum ada file terpilih</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <button type="button" @click="showImportModal = false" class="flex-1 px-6 py-4 bg-slate-100 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-200 transition-all">
                                Batal
                            </button>
                            <button type="submit" class="flex-2 px-8 py-4 bg-blue-600 rounded-2xl text-sm font-bold text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                                Mulai Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const fileInput = document.getElementById('file');
        const fileNameDisplay = document.getElementById('file-name-display');

        if (fileInput) {
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    fileNameDisplay.textContent = e.target.files[0].name;
                    fileNameDisplay.classList.remove('text-slate-400');
                    fileNameDisplay.classList.add('text-blue-600', 'font-bold');
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
