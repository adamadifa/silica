<x-app-layout>
    <x-breadcrumbs />
    
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
            <p class="text-slate-500 font-medium">Selamat datang kembali, {{ Auth::user()->name }}.</p>
        </div>
    </div>

    <!-- Clean Welcome Section -->
    <div class="bg-white p-12 rounded-[32px] shadow-sm border border-slate-50 flex flex-col items-center justify-center text-center min-h-[50vh]">
        <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-[24px] flex items-center justify-center mb-6 rotate-3">
            <svg class="w-10 h-10 -rotate-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Halo, {{ Auth::user()->name }}!</h2>
        <p class="text-slate-500 font-medium max-w-md mx-auto">Selamat bekerja hari ini. Silakan gunakan menu di sebelah kiri untuk mengelola master data dan fitur lainnya.</p>
    </div>
</x-app-layout>
