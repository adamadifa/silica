<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Studi Kasus Saya', 'url' => route('mahasiswa.perusahaan.index')],
        ['label' => 'Buat Baru']
    ]" />

    <div class="max-w-4xl">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Buat Studi Kasus Baru</h1>
            <p class="text-slate-500 font-medium text-xs">Siapkan entitas perusahaan untuk memulai praktik pencatatan.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[40px] p-12 shadow-sm border border-slate-50">
            <form action="{{ route('mahasiswa.perusahaan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf
                
                <!-- Logo & Basic Info -->
                <div class="flex flex-col md:flex-row gap-10">
                    <div class="w-full md:w-1/3">
                        <label class="block text-sm font-semibold text-slate-700 mb-4">Logo Perusahaan</label>
                        <div class="relative group">
                            <input type="file" name="logo" id="logo" class="hidden" accept="image/*" onchange="previewImage(this)">
                            <label for="logo" class="flex flex-col items-center justify-center w-full aspect-square bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl cursor-pointer hover:bg-slate-100 hover:border-blue-300 transition-all overflow-hidden relative">
                                <div id="preview-placeholder" class="text-center p-6">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Klik untuk Upload</p>
                                </div>
                                <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                            </label>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-2 text-center uppercase font-bold tracking-tighter italic">PNG/JPG Max 2MB</p>
                    </div>

                    <div class="flex-1 space-y-8">
                        <div class="mb-8">
                            <label for="kelas_id" class="block text-sm font-semibold text-slate-700 mb-3">Pilih Kelas</label>
                            @if($kelases->count() > 0)
                                <select name="kelas_id" id="kelas_id" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800" required>
                                    <option value="" disabled selected>-- Pilih Kelas --</option>
                                    @foreach($kelases as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }} ({{ $kelas->tahun_ajaran }})</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="p-4 bg-red-50 text-red-600 rounded-2xl text-xs font-bold border border-red-100 italic">
                                    Anda belum terdaftar di kelas manapun. Silakan hubungi Dosen Anda terlebih dahulu.
                                </div>
                            @endif
                            <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
                        </div>

                        <div>
                            <label for="nama_perusahaan" class="block text-sm font-semibold text-slate-700 mb-3">Nama Perusahaan / Entitas</label>
                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300" placeholder="Contoh: PT. Maju Bersama Sejahtera" required>
                            <x-input-error :messages="$errors->get('nama_perusahaan')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="telepon" class="block text-sm font-semibold text-slate-700 mb-3">No. Telepon</label>
                                <input type="text" name="telepon" id="telepon" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300" placeholder="021-xxxxxxx">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-3">Email Resmi</label>
                                <input type="email" name="email" id="email" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300" placeholder="info@company.com">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-semibold text-slate-700 mb-3">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="3" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300" placeholder="Jl. Raya Akuntansi No. 123, Jakarta Selatan"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 pt-4 border-t border-slate-50">
                    <div>
                        <label for="periode_awal" class="block text-sm font-semibold text-slate-700 mb-3">Periode Awal</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-6 flex items-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                    <path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M7 14h.01" /><path d="M10 14h.01" /><path d="M13 14h.01" /><path d="M16 14h.01" /><path d="M7 17h.01" /><path d="M10 17h.01" /><path d="M13 17h.01" /><path d="M16 17h.01" />
                                </svg>
                            </span>
                            <input type="text" 
                                   name="periode_awal" 
                                   id="periode_awal" 
                                   class="datepicker w-full pl-14 pr-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                                   placeholder="Pilih Tanggal Mulai"
                                   required>
                        </div>
                        <x-input-error :messages="$errors->get('periode_awal')" class="mt-2" />
                    </div>
                    <div>
                        <label for="periode_akhir" class="block text-sm font-semibold text-slate-700 mb-3">Periode Akhir</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-6 flex items-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                    <path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M7 14h.01" /><path d="M10 14h.01" /><path d="M13 14h.01" /><path d="M16 14h.01" /><path d="M7 17h.01" /><path d="M10 17h.01" /><path d="M13 17h.01" /><path d="M16 17h.01" />
                                </svg>
                            </span>
                            <input type="text" 
                                   name="periode_akhir" 
                                   id="periode_akhir" 
                                   class="datepicker w-full pl-14 pr-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                                   placeholder="Pilih Tanggal Selesai"
                                   required>
                        </div>
                        <x-input-error :messages="$errors->get('periode_akhir')" class="mt-2" />
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex items-center justify-center px-8 py-4 bg-blue-600 rounded-2xl text-base font-bold text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 group">
                        Simpan & Mulai Kerja
                        <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('preview-placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
