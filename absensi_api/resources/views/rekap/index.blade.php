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
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-800">REKAP <span class="text-blue-600">ABSENSI</span></h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Management System</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <form action="{{ route('rekap.index') }}" method="GET" class="flex items-center space-x-2">
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="bg-slate-100 border-none rounded-xl px-4 py-2 text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500 transition-all">
                </form>
                <form action="{{ route('rekap.bulk-hadir') }}" method="POST" onsubmit="return confirm('Tandai semua siswa hadir untuk tanggal ini?')">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <button type="submit" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-xl text-sm font-bold flex items-center transition-all">
                        <i class="fas fa-check-double mr-2"></i> Set Semua Hadir
                    </button>
                </form>
                <a href="{{ route('rekap.export', ['date' => $date]) }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-bold flex items-center transition-all shadow-lg shadow-emerald-100">
                    <i class="fas fa-file-export mr-2"></i> Export CSV
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <i class="fas fa-check-circle mr-3 text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <h3 class="text-xl font-extrabold text-slate-800">Daftar Absensi Siswa</h3>
                <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-slate-400 text-xs font-bold uppercase tracking-widest border-b border-slate-50">
                            <th class="py-6 px-8">Nama Siswa</th>
                            <th class="py-6 px-8">Kelas</th>
                            <th class="py-6 px-8">Jurusan</th>
                            <th class="py-6 px-8">Jam Masuk</th>
                            <th class="py-6 px-8">Status</th>
                            <th class="py-6 px-8 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($rekap as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-5 px-8">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm mr-4">
                                        {{ substr($item->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $item->name }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-sm font-semibold text-slate-600">{{ $item->kelas ?? '-' }}</span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-sm font-semibold text-slate-600">{{ $item->jurusan ?? '-' }}</span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-sm font-semibold {{ $item->time_in == '-' ? 'text-slate-300' : 'text-slate-600' }}">
                                    {{ $item->time_in }}
                                </span>
                            </td>
                            <td class="py-5 px-8">
                                @php
                                    $statusClasses = [
                                        'hadir' => 'bg-emerald-50 text-emerald-600',
                                        'alpha' => 'bg-red-50 text-red-600',
                                        'izin' => 'bg-blue-50 text-blue-600',
                                        'terlambat' => 'bg-amber-50 text-amber-600',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusClasses[$item->status] ?? 'bg-slate-50 text-slate-600' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-5 px-8 text-right">
                                <form action="{{ route('rekap.update') }}" method="POST" class="inline-flex items-center space-x-1">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $item->user_id }}">
                                    <input type="hidden" name="date" value="{{ $item->date }}">
                                    <select name="status" onchange="this.form.submit()" class="bg-slate-100 border-none rounded-lg px-3 py-1.5 text-[10px] font-bold text-slate-600 focus:ring-2 focus:ring-blue-500 cursor-pointer">
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
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-user-slash text-4xl text-slate-200 mb-4"></i>
                                    <p class="text-slate-400 font-medium">Tidak ada data siswa ditemukan.</p>
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

<!-- Add FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>
@endsection
