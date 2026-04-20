@props(['title', 'value', 'trend' => null, 'type' => 'up', 'color' => 'blue'])

<div class="bg-white p-6 rounded-[24px] shadow-sm border border-slate-50 transition-all hover:shadow-md group">
    <div class="flex justify-between items-start mb-4">
        <div class="w-12 h-12 bg-{{ $color }}-50 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
            @if($color == 'blue')
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @elseif($color == 'green')
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            @elseif($color == 'indigo')
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            @else
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @endif
        </div>
        @if($trend)
            <div class="flex items-center space-x-1 {{ $type == 'up' ? 'text-green-500' : 'text-red-500' }}">
                <span class="text-xs font-bold">{{ $trend }}%</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $type == 'up' ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"></path>
                </svg>
            </div>
        @endif
    </div>
    <div>
        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $title }}</h3>
        <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $value }}</p>
        <p class="text-[10px] font-medium text-slate-400 mt-2">vs bulan lalu <span class="text-slate-500 font-bold">Rp 12,000,000</span></p>
    </div>
</div>
