<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Jurnal Penyesuaian']
    ]" />

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Jurnal Penyesuaian</h1>
            <p class="text-slate-500 font-medium text-xs">Pencatatan akun secara akurat pada akhir periode akuntansi.</p>
        </div>
        @if(!request()->has('perusahaan_id'))
        <div>
            <a href="{{ route('mahasiswa.jurnal-penyesuaian.create') }}" class="flex items-center px-6 py-3 bg-blue-600 rounded-2xl text-sm font-bold text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Ayat Penyesuaian
            </a>
        </div>
        @endif
    </div>

    <!-- Jurnal Table -->
    <div class="bg-white rounded-[32px] shadow-sm border border-slate-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-40 text-center font-bold">Tanggal</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-40 text-center font-bold">No. Bukti</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keterangan / Akun</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right w-40 font-bold">Debit</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right w-40 font-bold">Kredit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($jurnals as $jurnal)
                        <!-- Transaction Row Header -->
                        <tr class="bg-slate-50/20 group/row">
                            <td class="px-8 py-4 text-xs font-bold text-slate-500 border-r border-slate-50 text-center">
                                {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="px-8 py-4 text-xs font-bold text-blue-600 border-r border-slate-50 text-center">
                                {{ $jurnal->nomor_bukti }}
                            </td>
                            <td class="px-8 py-4 text-sm font-black text-slate-800" colspan="3">
                                <div class="flex items-center justify-between">
                                    <span>{{ $jurnal->keterangan }}</span>
                                    
                                    @if(!request()->has('perusahaan_id'))
                                    <div class="flex items-center space-x-2 opacity-0 group-hover/row:opacity-100 transition-opacity">
                                        <a href="{{ route('mahasiswa.jurnal-penyesuaian.edit', $jurnal) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Edit Jurnal">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('mahasiswa.jurnal-penyesuaian.destroy', $jurnal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurnal penyesuaian ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Hapus Jurnal">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <!-- Detail Lines -->
                        @foreach($jurnal->jurnalDetails as $detail)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td colspan="2" class="border-r border-slate-50"></td>
                                <td class="px-12 py-3 text-sm font-medium {{ $detail->kredit > 0 ? 'pl-20 italic text-slate-500' : 'text-slate-700' }}">
                                    @if($detail->kredit > 0)
                                        <svg class="w-2 h-2 inline-block mr-2" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                    @endif
                                    {{ $detail->coa->kode_akun }} - {{ $detail->coa->nama_akun }}
                                </td>
                                <td class="px-8 py-3 text-right font-mono text-xs font-bold text-slate-600">
                                    {{ $detail->debit > 0 ? number_format($detail->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-8 py-3 text-right font-mono text-xs font-bold text-slate-600">
                                    {{ $detail->kredit > 0 ? number_format($detail->kredit, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                        <!-- Spacer Row -->
                        <tr class="h-2"></tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="text-slate-300 mb-4">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <p class="text-slate-400 font-medium">Belum ada jurnal penyesuaian yang dicatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
