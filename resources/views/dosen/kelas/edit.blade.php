<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Daftar Kelas', 'url' => route('dosen.kelas.index')],
        ['label' => 'Edit Kelas']
    ]" />

    <div class="max-w-4xl">
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Kelas</h1>
            <p class="text-slate-500 font-medium text-xs">Perbarui informasi kelas atau ubah dosen pengampu.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[32px] p-10 shadow-sm border border-slate-50">
            <form action="{{ route('dosen.kelas.update', $kela) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                @if(Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin')
                <div>
                    <label for="dosen_id" class="block text-sm font-semibold text-slate-700 mb-3">Dosen Pengampu</label>
                    <select name="dosen_id" id="dosen_id" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800" required>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_id', $kela->dosen_id) == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->name }} ({{ $dosen->email }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('dosen_id')" class="mt-2" />
                </div>
                @endif

                <div>
                    <label for="nama_kelas" class="block text-sm font-semibold text-slate-700 mb-3">Nama Kelas</label>
                    <input type="text" 
                           name="nama_kelas" 
                           id="nama_kelas" 
                           value="{{ old('nama_kelas', $kela->nama_kelas) }}"
                           class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                           placeholder="Contoh: Pengantar Akuntansi - Kelas A"
                           required>
                    <x-input-error :messages="$errors->get('nama_kelas')" class="mt-2" />
                </div>

                <div>
                    <label for="tahun_ajaran" class="block text-sm font-semibold text-slate-700 mb-3">Tahun Ajaran / Semester</label>
                    <input type="text" 
                           name="tahun_ajaran" 
                           id="tahun_ajaran" 
                           value="{{ old('tahun_ajaran', $kela->tahun_ajaran) }}"
                           class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                           placeholder="Contoh: 2024/2025 (Ganjil)"
                           required>
                    <x-input-error :messages="$errors->get('tahun_ajaran')" class="mt-2" />
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" class="flex-1 flex items-center justify-center px-8 py-4 bg-blue-600 rounded-2xl text-base font-bold text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 group">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('dosen.kelas.index') }}" class="px-8 py-4 bg-slate-100 rounded-2xl text-base font-bold text-slate-600 hover:bg-slate-200 transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
