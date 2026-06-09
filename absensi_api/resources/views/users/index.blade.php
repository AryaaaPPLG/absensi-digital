@extends('layouts.app')

@section('content')
<div class="min-h-screen font-['Plus_Jakarta_Sans']">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-black/40 backdrop-blur-xl border-b border-white/5 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/10 text-white">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-200">KELOLA <span class="sv-text-gradient">PENGGUNA</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-none">Admin Panel</p>
                </div>
            </div>
            
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:opacity-90 text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center transition-all shadow-lg shadow-blue-500/10">
                <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna
            </button>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        @if($errors->any())
            <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li class="text-sm font-medium">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="app-surface rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-slate-500 text-xs font-bold uppercase tracking-widest border-b border-white/5">
                            <th class="py-6 px-8">Nama</th>
                            <th class="py-6 px-8">Kelas / Jurusan</th>
                            <th class="py-6 px-8">Username / Email</th>
                            <th class="py-6 px-8">Role</th>
                            <th class="py-6 px-8">RFID UID</th>
                            <th class="py-6 px-8 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($users as $user)
                        <tr class="hover:bg-white/[0.03] transition-colors">
                            <td class="py-5 px-8">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-white/5 text-slate-400 flex items-center justify-center font-bold text-sm mr-4 border border-white/10">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-300">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-400">{{ $user->schoolClass?->nama_kelas ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-600 font-bold uppercase">{{ $user->schoolClass?->jurusan ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-400">{{ $user->username }}</span>
                                    <span class="text-[10px] text-slate-600 font-bold uppercase">{{ $user->email }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider 
                                    {{ $user->role == 'admin' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : ($user->role == 'guru' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'bg-white/5 text-slate-400 border border-white/10') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-xs font-mono font-bold {{ $user->rfid_uid ? 'text-blue-400' : 'text-slate-600' }}">
                                    {{ $user->rfid_uid ?? 'NOT SET' }}
                                </span>
                            </td>
                            <td class="py-5 px-8 text-right">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="editUser({{ json_encode($user) }})" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-slate-400 hover:bg-blue-500/10 hover:text-blue-400 transition-all border border-white/5">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition-all border border-white/5">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-6">
        <div class="bg-slate-900 border border-white/10 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-8 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-xl font-extrabold text-slate-200">Tambah Pengguna</h3>
                <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-500 hover:text-slate-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('users.store') }}" method="POST" class="p-8 space-y-4 max-h-[70vh] overflow-y-auto">
                @csrf
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Kelas</label>
                        <input type="text" name="kelas" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30" placeholder="Contoh: XII">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Jurusan</label>
                        <input type="text" name="jurusan" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30" placeholder="Contoh: RPL">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Username</label>
                    <input type="text" name="username" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Email</label>
                    <input type="email" name="email" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Password</label>
                    <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Role</label>
                    <select name="role" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:opacity-90 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-blue-500/10 mt-4">
                    Simpan Pengguna
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-6">
        <div class="bg-slate-900 border border-white/10 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden">
            <div class="p-8 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-xl font-extrabold text-slate-200">Edit Pengguna</h3>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-500 hover:text-slate-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-8 space-y-4 max-h-[70vh] overflow-y-auto">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="editName" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Kelas</label>
                        <input type="text" name="kelas" id="editKelas" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30" placeholder="Contoh: XII">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Jurusan</label>
                        <input type="text" name="jurusan" id="editJurusan" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30" placeholder="Contoh: RPL">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">RFID UID</label>
                    <input type="text" name="rfid_uid" id="editRfid" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Role</label>
                    <select name="role" id="editRole" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-2">Ganti Password (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-200 focus:ring-2 focus:ring-blue-500/30">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:opacity-90 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-blue-500/10 mt-4">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function editUser(user) {
        document.getElementById('editForm').action = '/users/' + user.id;
        document.getElementById('editName').value = user.name;
        document.getElementById('editKelas').value = user.school_class ? user.school_class.nama_kelas : '';
        document.getElementById('editJurusan').value = user.school_class ? user.school_class.jurusan : '';
        document.getElementById('editRole').value = user.role;
        document.getElementById('editRfid').value = user.rfid_uid || '';
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>

@endsection
