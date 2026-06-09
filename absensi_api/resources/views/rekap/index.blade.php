@extends('layouts.app')

@section('content')
<div class="min-h-screen font-['Plus_Jakarta_Sans']">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-black/40 backdrop-blur-xl border-b border-white/5 px-4 py-4 sm:px-6">
        <div class="max-w-7xl mx-auto flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="w-11 h-11 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex shrink-0 items-center justify-center shadow-lg shadow-blue-500/10 text-white">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="min-w-0">
                    <h1 class="text-lg sm:text-xl font-extrabold tracking-tight text-slate-200">REKAP <span class="sv-text-gradient">ABSENSI</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-none">Management System</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 lg:flex lg:items-center lg:space-x-4 lg:gap-0">
                <form action="{{ route('rekap.index') }}" method="GET" id="filterForm" class="flex items-center">
                    <input type="hidden" name="role" value="{{ $selectedRole }}">
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="min-h-11 w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm font-bold text-slate-300 focus:ring-2 focus:ring-blue-500/30 transition-all">
                </form>
                <form action="{{ route('rekap.bulk-hadir') }}" method="POST" onsubmit="return confirm('Tandai semua {{ $selectedRole }} hadir untuk tanggal ini?')">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="role" value="{{ $selectedRole }}">
                    <button type="submit" class="min-h-11 w-full justify-center bg-blue-500/10 hover:bg-blue-500/20 active:bg-blue-500/20 text-blue-400 px-4 py-2 rounded-xl text-sm font-bold flex items-center transition-all border border-blue-500/20">
                        <i class="fas fa-check-double mr-2"></i> Set Semua Hadir
                    </button>
                </form>
                <a href="{{ route('rekap.export', ['date' => $date, 'role' => $selectedRole]) }}" class="min-h-11 justify-center bg-emerald-500/10 hover:bg-emerald-500/20 active:bg-emerald-500/20 text-emerald-400 px-4 py-2 rounded-xl text-sm font-bold flex items-center transition-all border border-emerald-500/20">
                    <i class="fas fa-file-export mr-2"></i> Export CSV
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-6 sm:px-6 sm:py-10">
        <!-- Role Selection Tabs -->
        <div class="grid grid-cols-2 gap-3 mb-6 sm:flex sm:space-x-4 sm:gap-0 sm:mb-8">
            <a href="{{ route('rekap.index', ['date' => $date, 'role' => 'siswa']) }}" 
               class="min-h-12 px-4 sm:px-6 py-3 rounded-2xl font-bold text-sm transition-all flex items-center justify-center text-center {{ $selectedRole == 'siswa' ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg shadow-blue-500/10' : 'app-surface text-slate-400 hover:text-slate-200' }}">
                <i class="fas fa-user-graduate mr-2"></i> Siswa
            </a>
            <a href="{{ route('rekap.index', ['date' => $date, 'role' => 'guru']) }}" 
               class="min-h-12 px-4 sm:px-6 py-3 rounded-2xl font-bold text-sm transition-all flex items-center justify-center text-center {{ $selectedRole == 'guru' ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg shadow-blue-500/10' : 'app-surface text-slate-400 hover:text-slate-200' }}">
                <i class="fas fa-chalkboard-teacher mr-2"></i> Guru
            </a>
        </div>

        <div class="app-surface rounded-2xl sm:rounded-3xl overflow-hidden">
            <div class="p-5 sm:p-8 border-b border-white/5 flex flex-col gap-1 sm:flex-row sm:justify-between sm:items-center">
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-200">Daftar Absensi {{ ucfirst($selectedRole) }}</h3>
                <span class="text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</span>
            </div>

            <div class="md:hidden divide-y divide-white/5">
                @forelse($rekap as $item)
                    @php
                        $statusClasses = [
                            'hadir' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                            'alpha' => 'bg-red-500/10 text-red-400 border border-red-500/20',
                            'izin' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                            'terlambat' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                        ];
                    @endphp
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center">
                                <div class="w-11 h-11 rounded-full bg-blue-500/10 text-blue-400 flex shrink-0 items-center justify-center font-bold text-sm mr-3 border border-blue-500/20">
                                    {{ substr($item->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-extrabold text-slate-200">{{ $item->name }}</p>
                                    <p class="mt-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        {{ $item->kelas ?? '-' }} / {{ $item->jurusan ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <span class="shrink-0 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusClasses[$item->status] ?? 'bg-white/5 text-slate-400 border border-white/10' }}">
                                {{ $item->status }}
                            </span>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded-xl bg-white/[0.03] px-3 py-3 border border-white/5">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Masuk</span>
                                <span class="mt-1 block text-sm font-extrabold {{ $item->time_in == '-' ? 'text-slate-600' : 'text-slate-300' }}">{{ $item->time_in }}</span>
                            </div>
                            <div class="rounded-xl bg-white/[0.03] px-3 py-3 border border-white/5">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Pulang</span>
                                <span class="mt-1 block text-sm font-extrabold {{ $item->time_out == '-' ? 'text-slate-600' : 'text-slate-300' }}">{{ $item->time_out }}</span>
                            </div>
                        </div>

                        <form action="{{ route('rekap.update') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $item->user_id }}">
                            <input type="hidden" name="date" value="{{ $item->date }}">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Ubah Status</label>
                            <select name="status" onchange="this.form.submit()" class="min-h-11 w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm font-bold text-slate-300 focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                <option value="hadir" {{ $item->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="terlambat" {{ $item->status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                <option value="izin" {{ $item->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="alpha" {{ $item->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                            </select>
                        </form>
                    </div>
                @empty
                    <div class="py-16 px-5 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-user-slash text-4xl text-slate-700 mb-4"></i>
                            <p class="text-slate-500 font-medium">Tidak ada data {{ $selectedRole }} ditemukan.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-slate-500 text-xs font-bold uppercase tracking-widest border-b border-white/5">
                            <th class="py-6 px-8">Nama {{ ucfirst($selectedRole) }}</th>
                            <th class="py-6 px-8">Kelas</th>
                            <th class="py-6 px-8">Jurusan</th>
                            <th class="py-6 px-8">Jam Masuk</th>
                            <th class="py-6 px-8">Jam Pulang</th>
                            <th class="py-6 px-8">Status</th>
                            <th class="py-6 px-8 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($rekap as $item)
                        <tr class="hover:bg-white/[0.03] transition-colors">
                            <td class="py-5 px-8">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-500/10 text-blue-400 flex items-center justify-center font-bold text-sm mr-4 border border-blue-500/20">
                                        {{ substr($item->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-300">{{ $item->name }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-sm font-semibold text-slate-400">{{ $item->kelas ?? '-' }}</span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-sm font-semibold text-slate-400">{{ $item->jurusan ?? '-' }}</span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-sm font-semibold {{ $item->time_in == '-' ? 'text-slate-600' : 'text-slate-300' }}">
                                    {{ $item->time_in }}
                                </span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-sm font-semibold {{ $item->time_out == '-' ? 'text-slate-600' : 'text-slate-300' }}">
                                    {{ $item->time_out }}
                                </span>
                            </td>
                            <td class="py-5 px-8">
                                @php
                                    $statusClasses = [
                                        'hadir' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                        'alpha' => 'bg-red-500/10 text-red-400 border border-red-500/20',
                                        'izin' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                        'terlambat' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusClasses[$item->status] ?? 'bg-white/5 text-slate-400 border border-white/10' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-5 px-8 text-right">
                                <form action="{{ route('rekap.update') }}" method="POST" class="inline-flex items-center space-x-1">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $item->user_id }}">
                                    <input type="hidden" name="date" value="{{ $item->date }}">
                                    <select name="status" onchange="this.form.submit()" class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-[10px] font-bold text-slate-300 focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                        <option value="hadir" {{ $item->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="terlambat" {{ $item->status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                        <option value="izin" {{ $item->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="alpha" {{ $item->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-user-slash text-4xl text-slate-700 mb-4"></i>
                                    <p class="text-slate-500 font-medium">Tidak ada data {{ $selectedRole }} ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

@endsection
