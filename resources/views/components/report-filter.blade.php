@props(['month', 'year'])

@php
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    // Generate years range (current active period +- 2 years for context)
    $currentYear = date('Y');
    $years = range($currentYear - 2, $currentYear + 2);
@endphp

<div class="bg-white/80 backdrop-blur-md border border-slate-200/60 p-4 rounded-2xl shadow-sm mb-6 flex flex-wrap items-center justify-between gap-4 print:hidden">
    <div class="flex items-center space-x-3">
        <div class="p-2 bg-blue-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
        <div>
            <h4 class="text-xs font-bold text-slate-800 tracking-tight">Filter Periode</h4>
            <p class="text-[10px] text-slate-400 font-medium">Lihat riwayat laporan keuangan</p>
        </div>
    </div>

    <form action="{{ request()->url() }}" method="GET" class="flex items-center space-x-2">
        {{-- Preserve other query params like coa_id in Buku Besar --}}
        @foreach(request()->except(['month', 'year']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 transition-all">
            <select name="month" class="bg-transparent border-none text-xs font-bold text-slate-700 py-0 pl-0 pr-6 focus:ring-0">
                @foreach($months as $m => $name)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <div class="w-px h-4 bg-slate-200 mx-2"></div>
            <select name="year" class="bg-transparent border-none text-xs font-bold text-slate-700 py-0 pl-0 pr-6 focus:ring-0">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md shadow-blue-100 transition-all active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>
        
        @if(request()->hasAny(['month', 'year']))
            <a href="{{ request()->url() . (request()->has('coa_id') ? '?coa_id=' . request('coa_id') : '') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition-all" title="Reset Filter">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        @endif
    </form>
</div>
