<x-app-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Manajemen User', 'url' => route('admin.users.index')],
        ['label' => 'Daftar Pengguna']
    ]" />

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen User</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola data Dosen dan Mahasiswa di platform Silica.</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 rounded-2xl text-sm font-bold text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Pengguna Baru
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-slate-50 mb-8">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau NIM/NIP..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-medium text-slate-600">
            </div>
            <div class="w-full md:w-48">
                <select name="role" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-0 transition-all font-medium text-slate-600">
                    <option value="">Semua Role</option>
                    <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition-all">
                Filter
            </button>
            @if(request()->anyFilled(['q', 'role']))
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- User Table -->
    <div class="bg-white rounded-[40px] shadow-sm border border-slate-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">Pengguna</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">NIM / NIP</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50 text-center">Role</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold border-2 border-white shadow-sm overflow-hidden mr-4">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=E0F2FE&color=0369A1&bold=true" alt="Avatar">
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800 leading-tight">{{ $user->name }}</span>
                                    <span class="text-xs font-medium text-slate-400">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="font-mono text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded-lg">{{ $user->nim_nip }}</span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ 
                                $user->role == 'superadmin' ? 'bg-purple-100 text-purple-700' : (
                                $user->role == 'admin' ? 'bg-indigo-100 text-indigo-700' : (
                                $user->role == 'dosen' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700'
                            )) }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Edit User">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Semua data terkait mungkin akan terpengaruh.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Hapus User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <h3 class="text-slate-900 font-bold mb-1">Tidak Ada User Ditemukan</h3>
                                <p class="text-slate-500 text-sm font-medium">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
