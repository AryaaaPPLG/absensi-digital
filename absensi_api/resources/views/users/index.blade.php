@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-['Plus_Jakarta_Sans']">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-md border-b border-slate-200 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 text-white">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-800">KELOLA <span class="text-blue-600">PENGGUNA</span></h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Admin Panel</p>
                </div>
            </div>
            
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center transition-all shadow-lg shadow-blue-100">
                <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna
            </button>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <i class="fas fa-check-circle mr-3 text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li class="text-sm font-medium">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-slate-400 text-xs font-bold uppercase tracking-widest border-b border-slate-50">
                            <th class="py-6 px-8">Nama</th>
                            <th class="py-6 px-8">Kelas / Jurusan</th>
                            <th class="py-6 px-8">Username / Email</th>
                            <th class="py-6 px-8">Role</th>
                            <th class="py-6 px-8">RFID UID</th>
                            <th class="py-6 px-8 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-5 px-8">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-sm mr-4">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-600">{{ $user->kelas ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $user->jurusan ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-600">{{ $user->username }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $user->email }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider 
                                    {{ $user->role == 'admin' ? 'bg-purple-50 text-purple-600' : ($user->role == 'guru' ? 'bg-blue-50 text-blue-600' : 'bg-slate-50 text-slate-600') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-xs font-mono font-bold {{ $user->rfid_uid ? 'text-slate-700' : 'text-slate-300' }}">
                                    {{ $user->rfid_uid ?? 'NOT SET' }}
                                </span>
                            </td>
                            <td class="py-5 px-8 text-right">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="editUser({{ json_encode($user) }})" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition-all">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-600 transition-all">
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
    <div id="addModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-6">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <h3 class="text-xl font-extrabold text-slate-800">Tambah Pengguna</h3>
                <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('users.store') }}" method="POST" class="p-8 space-y-4 max-h-[70vh] overflow-y-auto">
                @csrf
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Kelas</label>
                        <input type="text" name="kelas" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500" placeholder="Contoh: XII">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Jurusan</label>
                        <input type="text" name="jurusan" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500" placeholder="Contoh: RPL">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Username</label>
                    <input type="text" name="username" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Email</label>
                    <input type="email" name="email" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Password</label>
                    <input type="password" name="password" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Role</label>
                    <select name="role" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-blue-100 mt-4">
                    Simpan Pengguna
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-6">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <h3 class="text-xl font-extrabold text-slate-800">Edit Pengguna</h3>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-8 space-y-4 max-h-[70vh] overflow-y-auto">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="editName" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Kelas</label>
                        <input type="text" name="kelas" id="editKelas" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500" placeholder="Contoh: XII">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Jurusan</label>
                        <input type="text" name="jurusan" id="editJurusan" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500" placeholder="Contoh: RPL">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">RFID UID</label>
                    <input type="text" name="rfid_uid" id="editRfid" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Role</label>
                    <select name="role" id="editRole" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Ganti Password (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-blue-100 mt-4">
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
        document.getElementById('editKelas').value = user.kelas || '';
        document.getElementById('editJurusan').value = user.jurusan || '';
        document.getElementById('editRole').value = user.role;
        document.getElementById('editRfid').value = user.rfid_uid || '';
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>
@endsection
