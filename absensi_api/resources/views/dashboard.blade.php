@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<style>
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .card-gradient-blue { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .card-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); }
    .card-gradient-purple { background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%); }
    
    .row-new { animation: pulse-blue 2s ease-out; }
    @keyframes pulse-blue {
        0% { background-color: rgba(59, 130, 246, 0.1); }
        100% { background-color: transparent; }
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-20">
    @if($user->role === 'admin')
        <!-- ADMIN DASHBOARD -->
        <div class="mb-10">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Ringkasan Sistem</h2>
            <p class="text-slate-500 font-medium mt-1">Pantau performa dan aktivitas absensi hari ini, {{ date('d F Y') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Total Users -->
            <div class="stat-card bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform duration-500 opacity-50"></div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 relative z-10">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="relative z-10">
                    <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Total Pengguna</span>
                    <div class="flex items-baseline space-x-2 mt-1">
                        <span class="text-4xl font-black text-slate-900">{{ $stats['total_users'] }}</span>
                        <span class="text-xs font-bold text-slate-400">Jiwa</span>
                    </div>
                    <div class="mt-4 flex items-center text-[10px] font-black uppercase tracking-wider text-slate-400">
                        <span class="text-blue-600">{{ $stats['total_guru'] }} Guru</span>
                        <span class="mx-2 opacity-30">|</span>
                        <span class="text-indigo-600">{{ $stats['total_siswa'] }} Siswa</span>
                    </div>
                </div>
            </div>

            <!-- Present Today -->
            <div class="stat-card bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform duration-500 opacity-50"></div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 relative z-10">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div class="relative z-10">
                    <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Hadir Hari Ini</span>
                    <div class="flex items-baseline space-x-2 mt-1">
                        <span class="text-4xl font-black text-slate-900" id="dashHadirToday">{{ $stats['attendance_today'] }}</span>
                        <span class="text-xs font-bold text-emerald-500">
                            <i class="fas fa-sync-alt fa-spin mr-1"></i> Live
                        </span>
                    </div>
                    <div class="mt-4 bg-emerald-50 rounded-lg py-1 px-3 inline-block">
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Terhitung Masuk</span>
                    </div>
                </div>
            </div>

            <!-- Late Today -->
            <div class="stat-card bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform duration-500 opacity-50"></div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 relative z-10">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="relative z-10">
                    <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Terlambat</span>
                    <div class="flex items-baseline space-x-2 mt-1">
                        <span class="text-4xl font-black text-slate-900" id="dashTerlambatToday">{{ $stats['terlambat_today'] }}</span>
                        <span class="text-xs font-bold text-amber-500">Siswa</span>
                    </div>
                    <div class="mt-4 bg-amber-50 rounded-lg py-1 px-3 inline-block">
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-600">Perlu Tindakan</span>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="stat-card card-gradient-blue p-6 rounded-[2rem] shadow-lg shadow-blue-200 text-white relative overflow-hidden">
                <i class="fas fa-microchip absolute -right-4 -bottom-4 text-7xl opacity-10 rotate-12"></i>
                <div class="w-12 h-12 rounded-2xl bg-white/20 text-white flex items-center justify-center mb-4">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                <span class="text-white/70 font-bold text-xs uppercase tracking-widest">Status Server</span>
                <div class="mt-1">
                    <span class="text-2xl font-black uppercase tracking-tight">Operasional</span>
                </div>
                <div class="mt-4 flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse shadow-lg shadow-green-400/50"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-white/90">Sistem Aktif & Terkoneksi</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Activity Table -->
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-7 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Aktivitas Terbaru</h3>
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mt-1">Live Feed</p>
                    </div>
                    <a href="{{ route('absensi.view') }}" class="px-5 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
                        View All
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] bg-white">
                                <th class="py-5 px-8">Nama / Role</th>
                                <th class="py-5 px-4">Waktu</th>
                                <th class="py-5 px-4 text-center">Status</th>
                                <th class="py-5 px-8 text-right">Metode</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50" id="dashActivityBody">
                            @forelse($stats['recent_attendances'] as $att)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-8">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-sm group-hover:bg-blue-600 group-hover:text-white transition-all">
                                            {{ substr($att->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800 leading-tight">{{ $att->user->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $att->user->role }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm font-bold text-slate-600">{{ $att->time_in }}</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $att->status === 'hadir' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                        {{ $att->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-8 text-right">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-100 px-3 py-1 rounded-lg">{{ $att->method ?? 'RFID' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-4 text-2xl">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada aktivitas hari ini</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions & Log -->
            <div class="space-y-8">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-slate-200">
                    <div class="relative z-10">
                        <h3 class="text-xl font-black mb-6 tracking-tight">Quick Actions</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Toggle Pulang -->
                            <form action="{{ route('admin.toggle-pulang') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full group bg-white/5 hover:bg-white/10 p-4 rounded-2xl flex items-center transition-all duration-300 border border-white/10">
                                    <div class="w-12 h-12 rounded-xl {{ $isClockOutOpen ? 'bg-emerald-500 shadow-emerald-500/50' : 'bg-red-500 shadow-red-500/50' }} flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform">
                                        <i class="fas {{ $isClockOutOpen ? 'fa-door-open' : 'fa-door-closed' }} text-lg"></i>
                                    </div>
                                    <div class="text-left">
                                        <span class="font-black block text-sm tracking-tight">Absensi Pulang</span>
                                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60">{{ $isClockOutOpen ? 'Dibuka' : 'Ditutup' }}</span>
                                    </div>
                                    <div class="ml-auto w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                                        <i class="fas fa-power-off text-xs"></i>
                                    </div>
                                </button>
                            </form>

                            <!-- AI Insight -->
                            <a href="{{ route('admin.ai-insight') }}" class="group bg-blue-600 p-4 rounded-2xl flex items-center transition-all duration-300 shadow-lg shadow-blue-900/50">
                                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-robot text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <span class="font-black block text-sm tracking-tight">AI Insights</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest opacity-80 italic text-blue-100">Analisis Perilaku</span>
                                </div>
                                <div class="ml-auto px-2 py-0.5 bg-white text-blue-600 rounded text-[8px] font-black uppercase">Beta</div>
                            </a>

                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('rekap.index') }}" class="bg-white/5 hover:bg-white/10 p-4 rounded-2xl flex flex-col items-center transition-all border border-white/10 text-center group">
                                    <i class="fas fa-file-invoice mb-2 text-slate-400 group-hover:text-white"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Rekap</span>
                                </a>
                                <a href="{{ route('users.index') }}" class="bg-white/5 hover:bg-white/10 p-4 rounded-2xl flex flex-col items-center transition-all border border-white/10 text-center group">
                                    <i class="fas fa-user-cog mb-2 text-slate-400 group-hover:text-white"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Users</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-meteor absolute -bottom-10 -right-10 text-[180px] text-white/5 rotate-45"></i>
                </div>

                <!-- System Log -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
                    <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                        <i class="fas fa-terminal text-blue-600 mr-3 text-sm"></i> System Log
                    </h3>
                    <div class="space-y-6">
                        @forelse($stats['system_logs'] as $log)
                        <div class="flex items-start space-x-4">
                            <div class="w-2 h-2 rounded-full bg-{{ $log->color }}-500 mt-2 shadow-[0_0_10px_rgba(var(--{{ $log->color }}-rgb),0.5)]"></div>
                            <div>
                                <p class="text-xs font-bold text-slate-700">{{ $log->activity }}</p>
                                <p class="text-[10px] text-slate-500 font-medium mt-0.5">{{ $log->description }}</p>
                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada log sistem</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- USER DASHBOARD (GURU/SISWA) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar: User Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden p-8 text-center group">
                    <div class="relative inline-block mb-6">
                        <div class="w-28 h-28 rounded-[2rem] bg-blue-50 text-blue-600 flex items-center justify-center text-4xl font-black mx-auto shadow-inner shadow-blue-100 group-hover:scale-105 transition-transform duration-500">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-10 h-10 rounded-2xl bg-white border-4 border-white shadow-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-shield-check text-sm"></i>
                        </div>
                    </div>
                    
                    <h2 class="text-2xl font-black text-slate-900 leading-tight mb-1">{{ $user->name }}</h2>
                    <span class="inline-block px-4 py-1.5 rounded-full bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-[0.2em] mb-8">
                        {{ $user->role }}
                    </span>

                    <div class="space-y-4 pt-8 border-t border-slate-50 text-left">
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-2xl">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">ID RFID</span>
                            <span class="text-xs font-mono font-black text-slate-700">{{ $user->rfid_uid ?? 'Unregistered' }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-2xl">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Username</span>
                            <span class="text-xs font-black text-slate-700">{{ $user->username }}</span>
                        </div>
                    </div>

                    <a href="{{ route('rfid.register.view') }}" class="mt-8 flex items-center justify-center space-x-3 w-full py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-slate-200">
                        <i class="fas fa-id-card"></i>
                        <span>Update Kartu</span>
                    </a>
                </div>

                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-blue-100">
                    <i class="fas fa-info-circle absolute -right-4 -top-4 text-7xl opacity-10 rotate-12"></i>
                    <p class="text-sm font-bold leading-relaxed mb-6 relative z-10">Selalu pastikan Anda melakukan scan kartu saat datang dan pulang untuk pendataan yang akurat.</p>
                    <div class="bg-white/20 h-1.5 rounded-full overflow-hidden relative z-10">
                        <div class="bg-white w-2/3 h-full rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Main Content: Stats & History -->
            <div class="lg:col-span-3 space-y-10">
                @if($user->role === 'guru')
                <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 relative overflow-hidden shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 group">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Panel Guru</h3>
                        <p class="text-slate-500 font-medium max-w-md">Kelola absensi siswa perwalian dan lihat laporan perkembangan belajar.</p>
                    </div>
                    <a href="{{ route('rekap.index') }}" class="relative z-10 flex items-center space-x-3 bg-blue-600 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 group-hover:-translate-y-1">
                        <i class="fas fa-chart-pie"></i>
                        <span>Buka Rekap</span>
                    </a>
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-slate-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                </div>
                @endif

                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Statistik Kehadiran</h2>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-white border border-slate-100 px-3 py-1 rounded-lg">Bulan Ini</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center group hover:border-emerald-200 transition-colors">
                            <span class="text-4xl font-black text-emerald-500 leading-none group-hover:scale-110 inline-block transition-transform">{{ $myStats['hadir'] }}</span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-3">Hadir</p>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center group hover:border-amber-200 transition-colors">
                            <span class="text-4xl font-black text-amber-500 leading-none group-hover:scale-110 inline-block transition-transform">{{ $myStats['terlambat'] }}</span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-3">Terlambat</p>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center group hover:border-blue-200 transition-colors">
                            <span class="text-4xl font-black text-blue-500 leading-none group-hover:scale-110 inline-block transition-transform">{{ $myStats['izin'] }}</span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-3">Izin</p>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center group hover:border-red-200 transition-colors">
                            <span class="text-4xl font-black text-red-500 leading-none group-hover:scale-110 inline-block transition-transform">{{ $myStats['alpha'] }}</span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-3">Alpha</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-8 py-7 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Riwayat Absensi</h3>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Last 5 Activities</span>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            @forelse($myStats['history'] as $h)
                            <div class="flex items-center justify-between p-5 bg-white rounded-2xl border border-slate-100 group hover:border-blue-100 hover:bg-blue-50/10 transition-all">
                                <div class="flex items-center space-x-5">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center shadow-sm group-hover:bg-white transition-colors">
                                        <span class="text-[9px] font-black text-slate-400 uppercase leading-none">{{ $h->date->format('M') }}</span>
                                        <span class="text-xl font-black text-slate-800 leading-none mt-1">{{ $h->date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <p class="text-sm font-black text-slate-800">Masuk: {{ $h->time_in }}</p>
                                            <span class="text-[8px] text-slate-400">•</span>
                                            <p class="text-sm font-bold text-slate-500">Pulang: {{ $h->time_out ?? '--:--' }}</p>
                                        </div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1.5 flex items-center">
                                            <i class="fas fa-microchip mr-2 text-[10px]"></i> {{ $h->method ?? 'RFID TERMINAL' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $h->status === 'hadir' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                    {{ $h->status }}
                                </span>
                            </div>
                            @empty
                            <div class="py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mx-auto mb-6 text-3xl">
                                    <i class="fas fa-ghost"></i>
                                </div>
                                <p class="text-slate-400 font-black uppercase tracking-widest text-xs">Belum ada catatan absensi</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dashActivityBody = document.getElementById('dashActivityBody');
        const dashHadirToday = document.getElementById('dashHadirToday');
        const dashTerlambatToday = document.getElementById('dashTerlambatToday');

        if (window.Echo) {
            window.Echo.channel('attendance-channel')
                .listen('.AttendanceScanned', (e) => {
                    console.log('Attendance received:', e);
                    
                    // Stats Update
                    if (e.type === 'in') {
                        if (e.status === 'hadir' && dashHadirToday) {
                            dashHadirToday.textContent = parseInt(dashHadirToday.textContent) + 1;
                        } else if (e.status === 'terlambat' && dashTerlambatToday) {
                            dashTerlambatToday.textContent = parseInt(dashTerlambatToday.textContent) + 1;
                        }
                    }

                    // Table Update (if on admin dashboard)
                    if (dashActivityBody) {
                        const newRow = `
                            <tr class="row-new group hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-8">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-sm">
                                            ${e.nama_siswa.charAt(0)}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800 leading-tight">${e.nama_siswa}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">${e.role || 'SISWA'}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm font-bold text-slate-600">${e.waktu_absen}</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest ${e.status === 'hadir' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'}">
                                        ${e.status}
                                    </span>
                                </td>
                                <td class="py-4 px-8 text-right">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-100 px-3 py-1 rounded-lg">${e.metode_rfid || 'RFID'}</span>
                                </td>
                            </tr>
                        `;

                        if (dashActivityBody.querySelector('td[colspan="4"]')) {
                            dashActivityBody.innerHTML = '';
                        }

                        dashActivityBody.insertAdjacentHTML('afterbegin', newRow);
                        if (dashActivityBody.children.length > 10) {
                            dashActivityBody.lastElementChild.remove();
                        }
                    }
                });
        }
    });
</script>
@endpush
