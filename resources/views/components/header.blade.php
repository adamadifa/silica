<header class="h-20 bg-white border-b border-slate-100 flex items-center justify-between px-8 sticky top-0 z-40">
    <!-- Left: Search Bar -->
    <div class="flex-1 max-w-md">
        <div class="relative group">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" 
                   class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-transparent rounded-2xl leading-5 focus:outline-none focus:bg-white focus:ring-0 focus:border-blue-600 sm:text-sm transition-all text-slate-900" 
                   placeholder="Cari data, laporan, atau akun...">
        </div>
    </div>

    <!-- Right: Notifications & User Info -->
    <div class="flex items-center space-x-6">
        <!-- Notification Button -->
        <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
        </button>

        <!-- User Info Badge (Desktop) -->
        <div class="hidden md:flex items-center ps-6 border-s border-slate-100">
            <div class="text-right me-3">
                <p class="text-sm font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">{{ Auth::user()->role }}</p>
            </div>
            <div class="w-10 h-10 rounded-full border-2 border-white shadow-sm overflow-hidden ring-1 ring-slate-100">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0284c7&color=fff" alt="Avatar">
            </div>
        </div>

        <!-- Mobile Menu Button -->
        <button @click="open = !open" class="lg:hidden p-2 text-slate-400 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
</header>
