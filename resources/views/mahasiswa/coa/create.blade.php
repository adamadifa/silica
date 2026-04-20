<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Bagan Akun', 'url' => route('mahasiswa.coa.index')],
        ['label' => 'Tambah Akun']
    ]" />

    <div class="max-w-5xl">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Akun Baru</h1>
            <p class="text-slate-500 font-medium text-xs">Pastikan kode akun dan saldo normal sudah sesuai dengan standar akuntansi.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[32px] p-10 shadow-sm border border-slate-50">
            <form action="{{ route('mahasiswa.coa.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="kode_akun" class="block text-sm font-semibold text-slate-700 mb-3">Kode Akun</label>
                        <input type="text" 
                               name="kode_akun" 
                               id="kode_akun" 
                               class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                               placeholder="Contoh: 1-101"
                               required>
                        <x-input-error :messages="$errors->get('kode_akun')" class="mt-2" />
                    </div>
                    <div>
                        <label for="nama_akun" class="block text-sm font-semibold text-slate-700 mb-3">Nama Akun</label>
                        <input type="text" 
                               name="nama_akun" 
                               id="nama_akun" 
                               class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                               placeholder="Contoh: Kas di Bank"
                               required>
                        <x-input-error :messages="$errors->get('nama_akun')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="kode_kategori" class="block text-sm font-semibold text-slate-700 mb-3">Kategori Akun</label>
                        <select name="kode_kategori" 
                                id="kode_kategori" 
                                class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800"
                                required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoriCoas as $kategori)
                                <option value="{{ $kategori->kode_kategori }}" {{ old('kode_kategori') == $kategori->kode_kategori ? 'selected' : '' }}>
                                    {{ $kategori->kode_kategori }} - {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kode_kategori')" class="mt-2" />
                    </div>
                    <div>
                        <label for="saldo_normal" class="block text-sm font-semibold text-slate-700 mb-3">Saldo Normal</label>
                        <div class="flex space-x-4">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="saldo_normal" value="debit" class="hidden peer" required>
                                <div class="px-6 py-4 bg-slate-50 rounded-2xl border-2 border-transparent peer-checked:border-blue-600 peer-checked:bg-blue-50 text-center font-bold text-slate-600 peer-checked:text-blue-600 transition-all">
                                    DEBIT
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="saldo_normal" value="kredit" class="hidden peer" required>
                                <div class="px-6 py-4 bg-slate-50 rounded-2xl border-2 border-transparent peer-checked:border-blue-600 peer-checked:bg-blue-50 text-center font-bold text-slate-600 peer-checked:text-blue-600 transition-all">
                                    KREDIT
                                </div>
                            </label>
                        </div>
                    </div>
                </div>



                <div class="pt-4">
                    <button type="submit" class="w-full flex items-center justify-center px-8 py-4 bg-blue-600 rounded-2xl text-base font-bold text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 group">
                        Simpan Akun
                        <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
