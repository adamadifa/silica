<div class="relative w-full h-full flex flex-col items-center justify-center p-8 overflow-hidden">
    <!-- Abstract Background Elements -->
    <div class="absolute -top-20 -right-20 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>

    <div class="relative z-10 w-full max-w-[340px] space-y-6">
        <!-- Main 'Ringkasan Kas' Card -->
        <div class="bg-white rounded-3xl shadow-xl p-6 relative">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-bold text-slate-800">Ringkasan Kas</h3>
                <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">Bulan Ini</span>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight leading-none mb-1">Pemasukan</p>
                        <p class="text-sm font-bold text-slate-900 leading-none">Rp 12.5M</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight leading-none mb-1">Pengeluaran</p>
                            <p class="text-sm font-bold text-slate-900 leading-none">Rp 8.2M</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Balance Card (Attached to Main Card) -->
            <div class="absolute -right-16 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white p-5 rounded-3xl shadow-2xl w-44 border-4 border-white">
                <p class="text-[10px] font-bold opacity-70 uppercase tracking-widest mb-1 leading-none">Total Saldo</p>
                <p class="text-xl font-bold leading-none mb-3">Rp 4.2M</p>
                <div class="flex -space-x-2">
                    <div class="w-6 h-6 rounded-full border-2 border-blue-600 bg-blue-200"></div>
                    <div class="w-6 h-6 rounded-full border-2 border-blue-600 bg-blue-300"></div>
                    <div class="w-6 h-6 rounded-full border-2 border-blue-600 bg-blue-400"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Text Content -->
    <div class="absolute bottom-12 left-10 right-10 text-white">
        <h2 class="text-2xl font-bold mb-3 leading-tight">Keuangan Teratur, Belajar Makin Seru.</h2>
        <p class="text-white/60 text-xs leading-relaxed max-w-[280px]">
            Platform pencatatan akuntansi yang didesain khusus untuk memudahkan mahasiswa dalam belajar.
        </p>
        
        <!-- Pagination Dots -->
        <div class="flex space-x-1.5 mt-6">
            <div class="w-6 h-1.5 bg-white rounded-full"></div>
            <div class="w-1.5 h-1.5 bg-white/30 rounded-full"></div>
            <div class="w-1.5 h-1.5 bg-white/30 rounded-full"></div>
        </div>
    </div>
</div>
