@props(['active'])

<aside {{ $attributes->merge(['class' => 'fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-100 flex flex-col transition-transform duration-300 lg:translate-x-0']) }} 
       :class="{'translate-x-0': open, '-translate-x-full': !open}">
    
    <!-- Branding Section -->
    <div class="px-8 py-10 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 transition-transform group-hover:scale-110">
                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7V17L12 22L22 17V7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 22V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M22 7L12 12L2 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="text-2xl font-bold tracking-tighter text-slate-800">Silica<span class="text-blue-600">.</span></span>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 px-4 py-4 space-y-8 overflow-y-auto">
        <!-- Main Menu -->
        <nav class="space-y-1">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Main Menu</p>
            
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="dashboard">
                Overview
            </x-sidebar-link>
            
            @if(in_array(Auth::user()->role, ['mahasiswa', 'dosen']))
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4">Accounting</p>
            <x-sidebar-link :href="route('mahasiswa.coa.index')" :active="request()->routeIs('mahasiswa.coa.*')" icon="coa">
                Bagan Akun
            </x-sidebar-link>
            <x-sidebar-link :href="route('mahasiswa.saldo-awal.index')" :active="request()->routeIs('mahasiswa.saldo-awal.*')" icon="trial-balance">
                Saldo Awal
            </x-sidebar-link>
            <x-sidebar-dropdown label="Jurnal" :active="request()->routeIs('mahasiswa.jurnal*')" icon="journal">
                <a href="{{ route('mahasiswa.jurnal.index') }}" 
                   class="block py-2 text-sm font-bold transition-all {{ request()->routeIs('mahasiswa.jurnal.index') ? 'text-blue-600' : 'text-slate-400 hover:text-blue-600' }}">
                   Jurnal Umum
                </a>
                <a href="{{ route('mahasiswa.jurnal-penyesuaian.index') }}" 
                   class="block py-2 text-sm font-bold transition-all {{ request()->routeIs('mahasiswa.jurnal-penyesuaian.*') ? 'text-blue-600' : 'text-slate-400 hover:text-blue-600' }}">
                   Jurnal Penyesuaian
                </a>
            </x-sidebar-dropdown>

            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4">Laporan Keuangan</p>
            <x-sidebar-link :href="route('mahasiswa.laporan.buku-besar')" :active="request()->routeIs('mahasiswa.laporan.buku-besar')" icon="book">
                Buku Besar
            </x-sidebar-link>
            <x-sidebar-link :href="route('mahasiswa.laporan.neraca-saldo')" :active="request()->routeIs('mahasiswa.laporan.neraca-saldo')" icon="trial-balance">
                Neraca Saldo
            </x-sidebar-link>
            <x-sidebar-link :href="route('mahasiswa.laporan.laba-rugi')" :active="request()->routeIs('mahasiswa.laporan.laba-rugi')" icon="profit">
                Laba Rugi
            </x-sidebar-link>
            <x-sidebar-link :href="route('mahasiswa.laporan.perubahan-ekuitas')" :active="request()->routeIs('mahasiswa.laporan.perubahan-ekuitas')" icon="trend">
                Perubahan Ekuitas
            </x-sidebar-link>
            <x-sidebar-link :href="route('mahasiswa.laporan.neraca')" :active="request()->routeIs('mahasiswa.laporan.neraca')" icon="balance-sheet">
                Posisi Keuangan
            </x-sidebar-link>
            <x-sidebar-link :href="route('mahasiswa.laporan.worksheet')" :active="request()->routeIs('mahasiswa.laporan.worksheet')" icon="spreadsheet">
                Neraca Lajur
            </x-sidebar-link>

            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4">Konfigurasi</p>
            <x-sidebar-link :href="route('mahasiswa.perusahaan.edit')" :active="request()->routeIs('mahasiswa.perusahaan.edit')" icon="settings">
                Profil Perusahaan Contoh
            </x-sidebar-link>
            @endif

            @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
            <x-sidebar-link :href="route('mahasiswa.perusahaan.create')" :active="request()->routeIs('mahasiswa.perusahaan.*')" icon="coa">
                Manajemen Perusahaan
            </x-sidebar-link>
            @endif
        </nav>

        @if(in_array(Auth::user()->role, ['superadmin', 'admin', 'dosen']))
        <!-- Management -->
        <nav class="space-y-1">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Manajemen</p>
            
            <x-sidebar-link :href="route('dosen.kelas.index')" :active="request()->routeIs('dosen.kelas.*')" icon="class">
                Data Kelas
            </x-sidebar-link>
            
            @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
            <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" icon="users">
                Data Pengguna
            </x-sidebar-link>
            @endif
        </nav>
        @endif
    </div>

    <!-- User Profile & Footer Section -->
    <div class="p-6 border-t border-slate-50 space-y-4">
        <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-2xl hover:bg-slate-50 transition-colors group">
            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold border-2 border-white shadow-sm overflow-hidden">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0284c7&color=fff" alt="Avatar">
            </div>
            <div class="ms-3 overflow-hidden">
                <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] font-medium text-slate-400 uppercase truncate tracking-wider">{{ Auth::user()->role }}</p>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 rounded-2xl transition-all group">
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
