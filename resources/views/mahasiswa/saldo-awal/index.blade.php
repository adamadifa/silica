<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Saldo Awal']
    ]" />

    <div class="mb-8 max-w-5xl">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Input Saldo Awal</h1>
        <p class="text-slate-500 font-medium text-xs">Pindahkan saldo dari neraca akhir periode sebelumnya ke dalam sistem.</p>
    </div>

    <div x-data="balanceCalculator()" class="space-y-6 max-w-5xl">
        <form action="{{ route('mahasiswa.saldo-awal.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded-[32px] shadow-sm border border-slate-50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-4 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap w-32">Kode Akun</th>
                                <th class="px-4 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">Nama Akun</th>
                                <th class="px-4 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right whitespace-nowrap w-56">Debit (Rp)</th>
                                <th class="px-4 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right whitespace-nowrap w-56">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($coas as $coa)
                            @php
                                $currentBalance = $coa->saldoAwals->first()?->balance ?? $coa->saldo_awal;
                            @endphp
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-4 py-2 text-sm font-bold text-slate-400">{{ $coa->kode_akun }}</td>
                                <td class="px-4 py-2">
                                    <p class="text-sm font-bold text-slate-800">{{ $coa->nama_akun }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">{{ $coa->kategori }}</p>
                                </td>
                                <td class="px-4 py-2">
                                @if($coa->saldo_normal === 'debit')
                                    @if(request()->has('perusahaan_id'))
                                        <div class="px-3 py-2 font-mono text-sm font-bold text-slate-700 text-right">
                                            {{ $currentBalance > 0 ? number_format($currentBalance, 0, ',', '.') : '-' }}
                                        </div>
                                    @else
                                        <input type="hidden" name="balances[{{ $coa->id }}]" :value="rows[{{ $coa->id }}].debit">
                                        <input type="text" 
                                               :value="formatNumber(rows[{{ $coa->id }}].debit)"
                                               @input="updateValue({{ $coa->id }}, 'debit', $event)"
                                               class="w-full px-3 py-2 bg-slate-50 border-transparent rounded-lg focus:bg-white focus:border-blue-600 focus:ring-0 text-right font-mono text-sm font-bold text-slate-700 transition-all"
                                               placeholder="0">
                                    @endif
                                @else
                                    <div class="text-right text-slate-300 font-mono text-sm pr-3">-</div>
                                @endif
                                </td>
                                <td class="px-4 py-2">
                                @if($coa->saldo_normal === 'kredit')
                                    @if(request()->has('perusahaan_id'))
                                        <div class="px-3 py-2 font-mono text-sm font-bold text-slate-700 text-right">
                                            {{ $currentBalance > 0 ? number_format($currentBalance, 0, ',', '.') : '-' }}
                                        </div>
                                    @else
                                        <input type="hidden" name="balances[{{ $coa->id }}]" :value="rows[{{ $coa->id }}].kredit">
                                        <input type="text" 
                                               :value="formatNumber(rows[{{ $coa->id }}].kredit)"
                                               @input="updateValue({{ $coa->id }}, 'kredit', $event)"
                                               class="w-full px-3 py-2 bg-slate-50 border-transparent rounded-lg focus:bg-white focus:border-indigo-600 focus:ring-0 text-right font-mono text-sm font-bold text-slate-700 transition-all"
                                               placeholder="0">
                                    @endif
                                @else
                                    <div class="text-right text-slate-300 font-mono text-sm pr-3">-</div>
                                @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-900 text-white">
                            <tr>
                                <td colspan="2" class="px-4 py-4 text-sm font-bold uppercase tracking-widest text-right whitespace-nowrap">Total Neraca Saldo Awal</td>
                                <td class="px-4 py-4 text-right font-mono text-lg font-black text-white whitespace-nowrap">
                                    Rp <span x-text="formatNumber(totalDebit) || '0'"></span>
                                </td>
                                <td class="px-4 py-4 text-right font-mono text-lg font-black text-white whitespace-nowrap">
                                    Rp <span x-text="formatNumber(totalKredit) || '0'"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Validation Bar -->
            @if(!request()->has('perusahaan_id'))
            <div class="sticky bottom-8 mt-10 p-6 bg-white rounded-3xl shadow-2xl border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center space-x-6">
                    <div class="flex flex-col">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Selisih (Difference)</p>
                        <p class="text-2xl font-black" :class="isBalanced ? 'text-green-500' : 'text-red-500'">
                            Rp <span x-text="formatNumber(Math.abs(totalDebit - totalKredit)) || '0'"></span>
                        </p>
                    </div>
                    <div class="h-10 w-px bg-slate-100 hidden md:block"></div>
                    <div>
                        <template x-if="isBalanced">
                            <div class="flex items-center text-green-600 font-bold text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Status: Seimbang (Balanced)
                            </div>
                        </template>
                        <template x-if="!isBalanced">
                            <div class="flex items-center text-red-500 font-bold text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Status: Masih Selisih
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex items-center space-x-4 w-full md:w-auto">
                    <button type="submit" 
                            :disabled="!isBalanced"
                            :class="!isBalanced ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-100'"
                            class="flex-1 md:flex-none px-12 py-4 rounded-2xl text-white font-bold transition-all">
                        Simpan Saldo Awal
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-8 py-4 text-slate-400 hover:text-slate-600 font-bold text-sm transition-colors">Batal</a>
                </div>
            </div>
            @else
            <div class="h-20"></div>
            @endif
        </form>
    </div>

    <script>
        function balanceCalculator() {
            return {
                rows: {
                    @foreach($coas as $coa)
                    {{ $coa->id }}: {
                        debit: {{ $coa->saldo_normal === 'debit' ? ($coa->saldoAwals->first()?->balance ?? $coa->saldo_awal) : 0 }},
                        kredit: {{ $coa->saldo_normal === 'kredit' ? ($coa->saldoAwals->first()?->balance ?? $coa->saldo_awal) : 0 }}
                    },
                    @endforeach
                },
                get totalDebit() {
                    return Object.values(this.rows).reduce((sum, row) => sum + (Number(row.debit) || 0), 0);
                },
                get totalKredit() {
                    return Object.values(this.rows).reduce((sum, row) => sum + (Number(row.kredit) || 0), 0);
                },
                get isBalanced() {
                    return Math.abs(this.totalDebit - this.totalKredit) < 0.01;
                },
                formatNumber(num) {
                    if (num === 0 || !num) return '';
                    return new Intl.NumberFormat('id-ID').format(num);
                },
                parseNumber(str) {
                    if (!str) return 0;
                    return Number(str.toString().replace(/[^0-9]/g, '')) || 0;
                },
                updateValue(id, type, event) {
                    let raw = this.parseNumber(event.target.value);
                    this.rows[id][type] = raw;
                    
                    let start = event.target.selectionStart;
                    let oldLen = event.target.value.length;
                    
                    event.target.value = this.formatNumber(raw);
                    
                    let newLen = event.target.value.length;
                    let newStart = start + (newLen - oldLen);
                    
                    // Delay setting selection range slightly to allow DOM to catch up, though direct assignment usually works
                    setTimeout(() => {
                        event.target.setSelectionRange(newStart, newStart);
                    }, 0);
                }
            }
        }
    </script>
</x-app-layout>
