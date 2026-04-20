<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Jurnal Umum', 'url' => route('mahasiswa.jurnal.index')],
        ['label' => 'Edit Transaksi']
    ]" />

    <style>
        /* Select2 Custom Styling with !important to override defaults */
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important; /* bg-slate-50 */
            border: 1px solid transparent !important;
            border-radius: 0.75rem !important; /* rounded-xl */
            height: 46px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.2s !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important; /* text-slate-700 */
            font-weight: 700 !important; /* font-bold */
            font-size: 0.875rem !important; /* text-sm */
            padding-left: 1rem !important;
            padding-right: 2.5rem !important;
            line-height: normal !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 12px !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--single {
            background-color: #ffffff !important;
            border-color: #4f46e5 !important; /* border-indigo-600 */
            box-shadow: none !important;
        }
        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
            z-index: 9999 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4f46e5 !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 6px 10px;
            font-size: 0.875rem;
        }
    </style>

    <div x-data="journalEntry()" class="pb-24 w-full" x-init="init()">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <span class="inline-block px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold mb-3">Mode Edit Transaksi</span>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Perbarui Jurnal Umum</h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Mengubah data transaksi yang sudah dicatat sebelumnya.</p>
            </div>
            <div class="hidden md:block text-right">
                <p class="text-xs font-bold text-slate-400 leading-none mb-1">Status Keseimbangan</p>
                <div :class="isBalanced ? 'text-green-600' : 'text-slate-300'" class="flex items-center justify-end font-black transition-colors">
                    <span x-text="isBalanced ? 'Seimbang' : 'Belum Seimbang'" class="text-xl"></span>
                    <div :class="isBalanced ? 'bg-green-500 shadow-lg shadow-green-200' : 'bg-slate-200'" class="w-2.5 h-2.5 rounded-full ml-3 animate-pulse"></div>
                </div>
            </div>
        </div>

        <form action="{{ route('mahasiswa.jurnal.update', $jurnal) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden w-full">
                <div class="bg-slate-50/50 px-8 py-5 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-700">Informasi Transaksi</h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                        <div class="md:col-span-3">
                            <label for="tanggal" class="block text-sm font-bold text-slate-500 mb-2 px-1">Tanggal</label>
                            <input type="text" name="tanggal" id="tanggal" value="{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('Y-m-d') }}" class="datepicker w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-0 transition-all font-bold text-slate-700 text-sm" required placeholder="Pilih Tanggal">
                        </div>

                        <div class="md:col-span-3">
                            <label for="nomor_bukti" class="block text-sm font-bold text-slate-500 mb-2 px-1">Nomor Bukti</label>
                            <input type="text" name="nomor_bukti" id="nomor_bukti" value="{{ $jurnal->nomor_bukti }}" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-0 transition-all font-bold text-slate-700 text-sm" placeholder="BM-001" required>
                        </div>

                        <div class="md:col-span-6">
                            <label for="keterangan" class="block text-sm font-bold text-slate-500 mb-2 px-1">Keterangan Ringkas</label>
                            <input type="text" name="keterangan" id="keterangan" value="{{ $jurnal->keterangan }}" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-0 transition-all font-bold text-slate-700 text-sm" placeholder="Contoh: Penerimaan modal awal dari pemilik" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden w-full">
                <div class="bg-slate-50/50 px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-700">Item Jurnal</h2>
                    <div class="flex items-center text-xs space-x-4">
                        <div class="flex items-center text-slate-400 font-bold">
                            <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></div>
                            Min. 2 Baris
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-slate-50">
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 text-left whitespace-nowrap min-w-[350px]">Pilih Akun / Rekening</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 text-right w-64 whitespace-nowrap">Debit</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 text-right w-64 whitespace-nowrap">Kredit</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 text-center w-24 whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="(row, index) in rows" :key="index">
                                <tr class="group hover:bg-slate-50/30 transition-colors">
                                    <td class="px-8 py-2.5">
                                        <select :id="'select2-' + index" 
                                                class="coa-select w-full" 
                                                required>
                                            <option value="">Cari Akun...</option>
                                            @foreach($coas as $coa)
                                                <option value="{{ $coa->id }}">{{ $coa->kode_akun }} - {{ $coa->nama_akun }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" :name="`details[${index}][coa_id]`" x-model="row.coa_id">
                                    </td>
                                    <td class="px-8 py-2.5">
                                        <input type="text" 
                                               :id="'debit-' + index"
                                               class="nominal-input w-full px-3 py-2 bg-slate-50 border-transparent rounded-lg focus:bg-white focus:border-indigo-600 focus:ring-0 transition-all text-right text-sm font-bold text-slate-800"
                                               placeholder="0">
                                        <input type="hidden" :name="`details[${index}][debit]`" x-model="row.debit">
                                    </td>
                                    <td class="px-8 py-2.5">
                                        <input type="text" 
                                               :id="'kredit-' + index"
                                               class="nominal-input w-full px-3 py-2 bg-slate-50 border-transparent rounded-lg focus:bg-white focus:border-indigo-600 focus:ring-0 transition-all text-right text-sm font-bold text-slate-800"
                                               placeholder="0">
                                        <input type="hidden" :name="`details[${index}][kredit]`" x-model="row.kredit">
                                    </td>
                                    <td class="px-8 py-2.5 text-center">
                                        <button type="button" 
                                                @click="removeRow(index)" 
                                                class="p-2 text-slate-300 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                :disabled="rows.length <= 2"
                                                :class="rows.length <= 2 ? 'opacity-20 cursor-not-allowed' : ''">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-indigo-900 text-white">
                            <tr>
                                <td class="px-8 py-4">
                                    <button type="button" @click="addRow()" class="inline-flex items-center text-xs font-bold text-indigo-200 hover:text-white transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Tambah baris baru
                                    </button>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <p class="text-[10px] font-bold text-indigo-300 leading-none mb-1">Total Debit</p>
                                    <p class="text-xl font-black" x-text="'Rp ' + formatNumber(totalDebit)"></p>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <p class="text-[10px] font-bold text-indigo-300 leading-none mb-1">Total Kredit</p>
                                    <p class="text-xl font-black" x-text="'Rp ' + formatNumber(totalKredit)"></p>
                                </td>
                                <td class="px-8 py-4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Enhanced Action Bar -->
            <div class="sticky bottom-0 left-0 right-0 z-50 py-6">
                <div class="w-full flex items-center justify-between bg-white/95 backdrop-blur-sm border border-slate-200 rounded-2xl p-4 shadow-2xl">
                    <div class="flex items-center space-x-12 px-6">
                        <div class="flex flex-col">
                            <p class="text-xs font-bold text-slate-400 leading-none mb-1.5">Selisih (Difference)</p>
                            <p class="text-2xl font-black transition-all" :class="isBalanced ? 'text-green-500' : 'text-red-500'" x-text="'Rp ' + formatNumber(Math.abs(totalDebit - totalKredit))"></p>
                        </div>
                        
                        <div class="h-10 w-px bg-slate-100 hidden md:block"></div>
                        
                        <div class="flex items-center space-x-4">
                            <div :class="isBalanced ? 'bg-green-100 text-green-600' : 'bg-red-50 text-red-500'" 
                                 class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors shadow-sm">
                                <svg x-show="isBalanced" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                <svg x-show="!isBalanced" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 mb-0.5">Status Keseimbangan</p>
                                <p :class="isBalanced ? 'text-green-600' : 'text-red-500'" class="text-sm font-black" x-text="isBalanced ? 'Jurnal Seimbang' : 'Belum Seimbang'"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 pr-6">
                        <a href="{{ route('mahasiswa.jurnal.index') }}" class="px-6 py-3.5 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Batal</a>
                        <button type="submit" 
                                :disabled="!isBalanced"
                                :class="!isBalanced ? 'bg-slate-100 text-slate-400 cursor-not-allowed opacity-50' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-100'"
                                class="px-12 py-4 rounded-xl text-sm font-black transition-all flex items-center group/btn shadow-sm">
                            <span>Perbarui Transaksi Jurnal</span>
                            <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>

    <script>
        function journalEntry() {
            return {
                rows: @json($jurnal->jurnalDetails->map(fn($d) => ['coa_id' => $d->coa_id, 'debit' => (float)$d->debit, 'kredit' => (float)$d->kredit])),
                init() {
                    this.$nextTick(() => {
                        this.initPlugins();
                    });
                },
                initPlugins() {
                    this.rows.forEach((row, index) => {
                        this.initializeRowPlugins(index);
                    });
                },
                initializeRowPlugins(index) {
                    const self = this;
                    
                    this.$nextTick(() => {
                        // Initialize Select2
                        const selectEl = $('#select2-' + index);
                        if (selectEl.length && !selectEl.hasClass('select2-hidden-accessible')) {
                            selectEl.select2({
                                placeholder: 'Cari Akun...',
                                width: '100%'
                            }).on('change', function(e) {
                                self.rows[index].coa_id = e.target.value;
                            });
                            
                            if (this.rows[index].coa_id) {
                                selectEl.val(this.rows[index].coa_id).trigger('change');
                            }
                        }

                        // Initialize Cleave for Debit
                        const debitEl = document.getElementById('debit-' + index);
                        if (debitEl && !debitEl.cleave) {
                            debitEl.cleave = new Cleave(debitEl, {
                                numeral: true,
                                numeralThousandsGroupStyle: 'thousand',
                                delimiter: '.',
                                numeralDecimalMark: ',',
                                onValueChanged: function(e) {
                                    self.rows[index].debit = e.target.rawValue || 0;
                                }
                            });
                            if (this.rows[index].debit) debitEl.cleave.setRawValue(this.rows[index].debit);
                        }

                        // Initialize Cleave for Kredit
                        const kreditEl = document.getElementById('kredit-' + index);
                        if (kreditEl && !kreditEl.cleave) {
                            kreditEl.cleave = new Cleave(kreditEl, {
                                numeral: true,
                                numeralThousandsGroupStyle: 'thousand',
                                delimiter: '.',
                                numeralDecimalMark: ',',
                                onValueChanged: function(e) {
                                    self.rows[index].kredit = e.target.rawValue || 0;
                                }
                            });
                            if (this.rows[index].kredit) kreditEl.cleave.setRawValue(this.rows[index].kredit);
                        }
                    });
                },
                addRow() {
                    this.rows.push({ coa_id: '', debit: 0, kredit: 0 });
                    this.$nextTick(() => {
                        this.initializeRowPlugins(this.rows.length - 1);
                    });
                },
                removeRow(index) {
                    if (this.rows.length > 2) {
                        this.rows.splice(index, 1);
                        this.$nextTick(() => {
                            // Re-initialize to fix any index mismatches in IDs
                            this.initPlugins();
                        });
                    }
                },
                get totalDebit() {
                    return this.rows.reduce((sum, row) => sum + parseFloat(row.debit || 0), 0);
                },
                get totalKredit() {
                    return this.rows.reduce((sum, row) => sum + parseFloat(row.kredit || 0), 0);
                },
                get isBalanced() {
                    return Math.abs(this.totalDebit - this.totalKredit) < 0.01 && this.totalDebit > 0;
                },
                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
