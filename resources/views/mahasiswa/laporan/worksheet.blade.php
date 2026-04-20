<x-app-layout>
    <style>
        .worksheet-container {
            max-width: 100%;
            overflow-x: auto;
            border-radius: 24px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            background: white;
            border: 1px solid #f1f5f9;
        }
        
        table.worksheet-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            min-width: 1500px; /* Force scroll */
        }

        .sticky-col {
            position: sticky;
            left: 0;
            background: white;
            z-index: 10;
            box-shadow: 2px 0 5px -2px rgba(0,0,0,0.1);
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 20;
            background: #f8fafc;
        }

        .header-group {
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.1em;
            padding: 12px 8px;
            text-align: center;
        }

        .header-sub {
            background: #f1f5f9;
            font-size: 9px;
            font-weight: 800;
            color: #64748b;
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .cell-data {
            padding: 10px 12px;
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            font-size: 11px;
            text-align: right;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .cell-acc {
            padding: 10px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #0f172a;
            border-bottom: 1px solid #f1f5f9;
        }

        .bg-ns { background-color: rgba(59, 130, 246, 0.03); }  /* Blue */
        .bg-ajp { background-color: rgba(139, 92, 246, 0.03); } /* Violet */
        .bg-nscp { background-color: rgba(20, 184, 166, 0.03); } /* Teal */
        .bg-lr { background-color: rgba(245, 158, 11, 0.03); }  /* Amber */
        .bg-n { background-color: rgba(99, 102, 241, 0.03); }   /* Indigo */

        .row-hover:hover { background-color: #f8fafc; }
        .row-hover:hover .sticky-col { background-color: #f8fafc; }

        .total-row {
            background: #f8fafc;
            font-weight: 800;
        }
        
        .laba-rugi-row {
            background: #f0fdf4;
            color: #166534;
        }
    </style>

    <x-breadcrumbs :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Neraca Lajur (Worksheet)']
    ]" />

    <div class="mb-8 flex items-center justify-between no-print">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Neraca Lajur (Worksheet)</h1>
            <p class="text-slate-500 font-medium text-xs">Kertas kerja 10 kolom untuk verifikasi siklus akuntansi periode ini.</p>
        </div>
        <div class="flex items-center space-x-3">
             <div class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-bold uppercase border border-blue-100 italic">
                Siklus Berakhir: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('d F Y') }}
             </div>
        </div>
    </div>

    <x-report-filter :month="$month" :year="$year" />

    <div class="worksheet-container">
        <table class="worksheet-table">
            <thead class="sticky-header">
                <tr>
                    <th rowspan="2" class="sticky-col header-group bg-white" style="text-align: left; min-width: 250px;">Keterangan / Nama Akun</th>
                    <th colspan="2" class="header-group text-blue-600">Neraca Saldo</th>
                    <th colspan="2" class="header-group text-violet-600">Penyesuaian</th>
                    <th colspan="2" class="header-group text-teal-600">NS Setelah Penyesuaian</th>
                    <th colspan="2" class="header-group text-amber-600">Laba Rugi</th>
                    <th colspan="2" class="header-group text-indigo-600">Neraca</th>
                </tr>
                <tr>
                    <th class="header-sub">Debit</th><th class="header-sub">Kredit</th>
                    <th class="header-sub">Debit</th><th class="header-sub">Kredit</th>
                    <th class="header-sub">Debit</th><th class="header-sub">Kredit</th>
                    <th class="header-sub">Debit</th><th class="header-sub">Kredit</th>
                    <th class="header-sub">Debit</th><th class="header-sub">Kredit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                <tr class="row-hover">
                    <td class="sticky-col cell-acc">
                        <span class="text-[10px] text-slate-400 font-mono block">{{ $row->coa->kode_akun }}</span>
                        {{ $row->coa->nama_akun }}
                    </td>
                    <td class="cell-data bg-ns">{{ number_format($row->ns_debit, 0, ',', '.') }}</td>
                    <td class="cell-data bg-ns">{{ number_format($row->ns_kredit, 0, ',', '.') }}</td>
                    
                    <td class="cell-data bg-ajp">{{ number_format($row->ajp_debit, 0, ',', '.') }}</td>
                    <td class="cell-data bg-ajp">{{ number_format($row->ajp_kredit, 0, ',', '.') }}</td>
                    
                    <td class="cell-data bg-nscp">{{ number_format($row->nscp_debit, 0, ',', '.') }}</td>
                    <td class="cell-data bg-nscp">{{ number_format($row->nscp_kredit, 0, ',', '.') }}</td>
                    
                    <td class="cell-data bg-lr">{{ number_format($row->lr_debit, 0, ',', '.') }}</td>
                    <td class="cell-data bg-lr">{{ number_format($row->lr_kredit, 0, ',', '.') }}</td>
                    
                    <td class="cell-data bg-n">{{ number_format($row->n_debit, 0, ',', '.') }}</td>
                    <td class="cell-data bg-n">{{ number_format($row->n_kredit, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td class="sticky-col cell-acc bg-slate-50">JUMLAH TOTAL</td>
                    <td class="cell-data bg-blue-50/50">{{ number_format($totals->ns_d, 0, ',', '.') }}</td>
                    <td class="cell-data bg-blue-50/50">{{ number_format($totals->ns_k, 0, ',', '.') }}</td>
                    
                    <td class="cell-data bg-violet-50/50">{{ number_format($totals->ajp_d, 0, ',', '.') }}</td>
                    <td class="cell-data bg-violet-50/50">{{ number_format($totals->ajp_k, 0, ',', '.') }}</td>
                    
                    <td class="cell-data bg-teal-50/50">{{ number_format($totals->nscp_d, 0, ',', '.') }}</td>
                    <td class="cell-data bg-teal-50/50">{{ number_format($totals->nscp_k, 0, ',', '.') }}</td>
                    
                    <td class="cell-data bg-amber-50/50">{{ number_format($totals->lr_d, 0, ',', '.') }}</td>
                    <td class="cell-data bg-amber-50/50">{{ number_format($totals->lr_k, 0, ',', '.') }}</td>
                    
                    <td class="cell-data bg-indigo-50/50">{{ number_format($totals->n_d, 0, ',', '.') }}</td>
                    <td class="cell-data bg-indigo-50/50">{{ number_format($totals->n_k, 0, ',', '.') }}</td>
                </tr>

                {{-- Laba / Rugi Bersih Row --}}
                <tr class="laba-rugi-row">
                    <td class="sticky-col cell-acc bg-emerald-50">LABA / (RUGI) BERSIH</td>
                    <td colspan="6" class="bg-white"></td>
                    
                    {{-- Laba Rugi Balancing --}}
                    <td class="cell-data">{{ $laba_rugi_nominal < 0 ? number_format(abs($laba_rugi_nominal), 0, ',', '.') : '' }}</td>
                    <td class="cell-data">{{ $laba_rugi_nominal >= 0 ? number_format($laba_rugi_nominal, 0, ',', '.') : '' }}</td>
                    
                    {{-- Neraca Balancing --}}
                    <td class="cell-data">{{ $laba_rugi_nominal >= 0 ? number_format($laba_rugi_nominal, 0, ',', '.') : '' }}</td>
                    <td class="cell-data">{{ $laba_rugi_nominal < 0 ? number_format(abs($laba_rugi_nominal), 0, ',', '.') : '' }}</td>
                </tr>

                {{-- Grand Total --}}
                <tr class="total-row">
                    <td class="sticky-col cell-acc bg-slate-900 !text-white uppercase text-[10px] font-black">Grand Total Balanced</td>
                    <td colspan="6" class="bg-white"></td>
                    
                    <td class="cell-data !bg-slate-900 !text-white font-bold">{{ number_format($totals->lr_d + ($laba_rugi_nominal < 0 ? abs($laba_rugi_nominal) : 0), 0, ',', '.') }}</td>
                    <td class="cell-data !bg-slate-900 !text-white font-bold">{{ number_format($totals->lr_k + ($laba_rugi_nominal >= 0 ? $laba_rugi_nominal : 0), 0, ',', '.') }}</td>
                    
                    <td class="cell-data !bg-slate-900 !text-white font-bold">{{ number_format($totals->n_d + ($laba_rugi_nominal >= 0 ? $laba_rugi_nominal : 0), 0, ',', '.') }}</td>
                    <td class="cell-data !bg-slate-900 !text-white font-bold">{{ number_format($totals->n_k + ($laba_rugi_nominal < 0 ? abs($laba_rugi_nominal) : 0), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</x-app-layout>
