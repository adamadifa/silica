<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Manajemen User', 'url' => route('admin.users.index')],
        ['label' => 'Tambah Pengguna']
    ]" />

    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tambah Pengguna Baru</h1>
            <p class="text-slate-500 font-medium">Lengkapi formulir di bawah ini untuk mendaftarkan akun baru.</p>
        </div>

        <div class="bg-white rounded-[40px] p-8 md:p-12 shadow-sm border border-slate-50">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nama Lengkap -->
                    <div class="col-span-2">
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-3">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                               placeholder="Masukkan nama lengkap..." required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-3">Email Resmi</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                               class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                               placeholder="email@contoh.com" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- NIM/NIP -->
                    <div>
                        <label for="nim_nip" class="block text-sm font-semibold text-slate-700 mb-3">NIM / NIP</label>
                        <input type="text" name="nim_nip" id="nim_nip" value="{{ old('nim_nip') }}" 
                               class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                               placeholder="Masukkan NIM atau NIP..." required>
                        <x-input-error :messages="$errors->get('nim_nip')" class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div class="col-span-2">
                        <label for="role" class="block text-sm font-semibold text-slate-700 mb-3">Pilih Role Pengguna</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="relative flex items-center p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all border-2 border-transparent has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 group">
                                <input type="radio" name="role" value="dosen" class="hidden" {{ old('role') == 'dosen' ? 'checked' : '' }} required>
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800">Dosen</span>
                                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Pengampu Kelas</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all border-2 border-transparent has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 group">
                                <input type="radio" name="role" value="mahasiswa" class="hidden" {{ old('role', 'mahasiswa') == 'mahasiswa' ? 'checked' : '' }}>
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-emerald-600 shadow-sm mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800">Mahasiswa</span>
                                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Praktikan</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all border-2 border-transparent has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 group">
                                <input type="radio" name="role" value="admin" class="hidden" {{ old('role') == 'admin' ? 'checked' : '' }}>
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800">Admin</span>
                                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Administrator</span>
                                </div>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-3">Password</label>
                        <input type="password" name="password" id="password" 
                               class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                               placeholder="********" required>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-3">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-bold text-slate-800 placeholder-slate-300"
                               placeholder="********" required>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-50 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                        Simpan Akun Pengguna
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
