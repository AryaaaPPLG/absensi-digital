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
    .card-gradient-blue { background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(99, 102, 241, 0.15) 100%); border: 1px solid rgba(59, 130, 246, 0.2); }
    .card-gradient-indigo { background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(139, 92, 246, 0.15) 100%); border: 1px solid rgba(99, 102, 241, 0.2); }
    .card-gradient-purple { background: linear-gradient(135deg, rgba(168, 85, 247, 0.2) 0%, rgba(139, 92, 246, 0.15) 100%); border: 1px solid rgba(168, 85, 247, 0.2); }
    
    .row-new { animation: pulse-blue 2s ease-out; }
    .dashboard-shell { animation: dashboard-rise 520ms ease-out both; }
    .hero-panel {
        background:
            linear-gradient(135deg, rgba(15, 23, 42, 0.94), rgba(10, 10, 15, 0.95)),
            radial-gradient(circle at 84% 30%, rgba(59, 130, 246, 0.15), transparent 18rem);
        border: 1px solid rgba(59, 130, 246, 0.12);
    }
    .metric-spark {
        position: absolute;
        inset: auto 1.25rem 1.25rem auto;
        width: 4.5rem;
        height: 2.5rem;
        opacity: 0.12;
        background: linear-gradient(135deg, transparent 42%, currentColor 43% 48%, transparent 49%),
                    linear-gradient(45deg, transparent 52%, currentColor 53% 58%, transparent 59%);
    }
    @keyframes dashboard-rise {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse-blue {
        0% { background-color: rgba(59, 130, 246, 0.15); }
        100% { background-color: transparent; }
    }
</style>
@endsection

@section('content')
<div class="dashboard-shell max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-20">
    @if($user->role === 'admin')
        <!-- ADMIN DASHBOARD -->
        <div class="hero-panel mb-10 rounded-[2rem] p-8 md:p-10 text-white relative overflow-hidden shadow-2xl shadow-blue-500/5">
            <div class="absolute inset-0 scan-lines opacity-10"></div>
            <div class="orbital-ring"></div>
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-blue-500/20 bg-blue-500/10 px-4 py-2 text-[10px] font-black uppercase tracking-[0.2em] text-blue-300 mb-5">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Live Control Center
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-100">Ringkasan Sistem</h2>
                    <p class="text-slate-400 font-medium mt-3 max-w-2xl">Pantau performa, scan RFID, dan aktivitas absensi hari ini, {{ date('d F Y') }}.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 min-w-full sm:min-w-[24rem]">
                    <div class="rounded-2xl border border-blue-500/15 bg-blue-500/5 p-4 backdrop-blur-sm">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Server</p>
                        <p class="mt-1 text-sm font-black text-emerald-400">Online</p>
                    </div>
                    <div class="rounded-2xl border border-purple-500/15 bg-purple-500/5 p-4 backdrop-blur-sm">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">RFID</p>
                        <p class="mt-1 text-sm font-black text-blue-300">Ready</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Total Users -->
            <div class="stat-card app-card-hover app-surface p-6 rounded-[2rem] relative overflow-hidden group text-blue-400">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="metric-spark"></div>
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center mb-4 relative z-10 border border-blue-500/20">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="relative z-10">
                    <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Total Pengguna</span>
                    <div class="flex items-baseline space-x-2 mt-1">
                        <span class="text-4xl font-black text-slate-100">{{ $stats['total_users'] }}</span>
                        <span class="text-xs font-bold text-slate-500">Jiwa</span>
                    </div>
                    <div class="mt-4 flex items-center text-[10px] font-black uppercase tracking-wider text-slate-500">
                        <span class="text-blue-400">{{ $stats['total_guru'] }} Guru</span>
                        <span class="mx-2 opacity-30">|</span>
                        <span class="text-purple-400">{{ $stats['total_siswa'] }} Siswa</span>
                    </div>
                </div>
            </div>

            <!-- Present Today -->
            <div class="stat-card app-card-hover app-surface p-6 rounded-[2rem] relative overflow-hidden group text-emerald-400">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="metric-spark"></div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-4 relative z-10 border border-emerald-500/20">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div class="relative z-10">
                    <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Hadir Hari Ini</span>
                    <div class="flex items-baseline space-x-2 mt-1">
                        <span class="text-4xl font-black text-slate-100" id="dashHadirToday">{{ $stats['attendance_today'] }}</span>
                        <span class="text-xs font-bold text-emerald-400">
                            <i class="fas fa-sync-alt fa-spin mr-1"></i> Live
                        </span>
                    </div>
                    <div class="mt-4 bg-emerald-500/10 rounded-lg py-1 px-3 inline-block border border-emerald-500/15">
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">Terhitung Masuk</span>
                    </div>
                </div>
            </div>

            <!-- Late Today -->
            <div class="stat-card app-card-hover app-surface p-6 rounded-[2rem] relative overflow-hidden group text-amber-400">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="metric-spark"></div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center mb-4 relative z-10 border border-amber-500/20">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="relative z-10">
                    <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Terlambat</span>
                    <div class="flex items-baseline space-x-2 mt-1">
                        <span class="text-4xl font-black text-slate-100" id="dashTerlambatToday">{{ $stats['terlambat_today'] }}</span>
                        <span class="text-xs font-bold text-amber-400">Siswa</span>
                    </div>
                    <div class="mt-4 bg-amber-500/10 rounded-lg py-1 px-3 inline-block border border-amber-500/15">
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-400">Perlu Tindakan</span>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="stat-card card-gradient-blue p-6 rounded-[2rem] shadow-lg shadow-blue-500/5 text-white relative overflow-hidden">
                <div class="orbital-ring"></div>
                <i class="fas fa-microchip absolute -right-4 -bottom-4 text-7xl opacity-5 rotate-12"></i>
                <div class="w-12 h-12 rounded-2xl bg-blue-500/20 text-blue-300 flex items-center justify-center mb-4 border border-blue-500/20">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                <span class="text-blue-200/60 font-bold text-xs uppercase tracking-widest">Status Server</span>
                <div class="mt-1">
                    <span class="text-2xl font-black uppercase tracking-tight text-slate-100">Operasional</span>
                </div>
                <div class="mt-4 flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse shadow-lg shadow-emerald-400/50"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-200/70">Sistem Aktif & Terkoneksi</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Activity Table -->
            <div class="lg:col-span-2 app-surface rounded-[2rem] overflow-hidden">
                <div class="px-8 py-7 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                    <div>
                        <h3 class="text-xl font-black text-slate-200 tracking-tight">Aktivitas Terbaru</h3>
                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mt-1">Live Feed</p>
                    </div>
                    <a href="{{ route('absensi.view') }}" class="px-5 py-2 bg-white/5 border border-white/10 text-slate-300 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-white/10 transition-all">
                        View All
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] bg-white/[0.02]">
                                <th class="py-5 px-8">Nama / Role</th>
                                <th class="py-5 px-4">Waktu</th>
                                <th class="py-5 px-4 text-center">Status</th>
                                <th class="py-5 px-8 text-right">Metode</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5" id="dashActivityBody">
                            @forelse($stats['recent_attendances'] as $att)
                            <tr class="group hover:bg-white/[0.03] transition-colors">
                                <td class="py-4 px-8">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 font-black text-sm group-hover:bg-blue-500/20 group-hover:text-blue-400 transition-all border border-white/5">
                                            {{ substr($att->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-200 leading-tight">{{ $att->user->name }}</span>
                                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ $att->user->role }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm font-bold text-slate-400">{{ $att->time_in }}</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $att->status === 'hadir' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                        {{ $att->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-8 text-right">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] bg-white/5 px-3 py-1 rounded-lg border border-white/5">{{ $att->method ?? 'RFID' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center text-slate-600 mb-4 text-2xl border border-white/5">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Belum ada aktivitas hari ini</p>
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
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-blue-500/5 border border-white/5">
                    <div class="absolute inset-0 scan-lines opacity-10"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-black mb-6 tracking-tight text-slate-200">Quick Actions</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Toggle Pulang -->
                            <form action="{{ route('admin.toggle-pulang') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full group bg-white/5 hover:bg-white/10 p-4 rounded-2xl flex items-center transition-all duration-300 border border-white/10">
                                    <div class="w-12 h-12 rounded-xl {{ $isClockOutOpen ? 'bg-emerald-500/20 shadow-emerald-500/20 border border-emerald-500/30' : 'bg-red-500/20 shadow-red-500/20 border border-red-500/30' }} flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform">
                                        <i class="fas {{ $isClockOutOpen ? 'fa-door-open text-emerald-400' : 'fa-door-closed text-red-400' }} text-lg"></i>
                                    </div>
                                    <div class="text-left">
                                        <span class="font-black block text-sm tracking-tight text-slate-200">Absensi Pulang</span>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">{{ $isClockOutOpen ? 'Dibuka' : 'Ditutup' }}</span>
                                    </div>
                                    <div class="ml-auto w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-white/20 transition-colors text-slate-400">
                                        <i class="fas fa-power-off text-xs"></i>
                                    </div>
                                </button>
                            </form>

                            <!-- AI Insight -->
                            <a href="{{ route('admin.ai-insight') }}" class="group bg-gradient-to-r from-blue-600/20 to-purple-600/20 p-4 rounded-2xl flex items-center transition-all duration-300 border border-blue-500/20 hover:border-blue-500/40">
                                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform border border-blue-500/20">
                                    <i class="fas fa-robot text-blue-400 text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <span class="font-black block text-sm tracking-tight text-slate-200">AI Insights</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-300/60 italic">Analisis Perilaku</span>
                                </div>
                                <div class="ml-auto px-2 py-0.5 bg-blue-500/20 text-blue-300 rounded text-[8px] font-black uppercase border border-blue-500/20">Beta</div>
                            </a>

                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('rekap.index') }}" class="bg-white/5 hover:bg-white/10 p-4 rounded-2xl flex flex-col items-center transition-all border border-white/10 text-center group">
                                    <i class="fas fa-file-invoice mb-2 text-slate-500 group-hover:text-blue-400 transition-colors"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Rekap</span>
                                </a>
                                <a href="{{ route('users.index') }}" class="bg-white/5 hover:bg-white/10 p-4 rounded-2xl flex flex-col items-center transition-all border border-white/10 text-center group">
                                    <i class="fas fa-user-cog mb-2 text-slate-500 group-hover:text-blue-400 transition-colors"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Users</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-meteor absolute -bottom-10 -right-10 text-[180px] text-white/[0.02] rotate-45"></i>
                </div>

                <!-- System Log -->
                <div class="app-surface rounded-[2rem] p-8">
                    <h3 class="text-lg font-black text-slate-200 mb-6 flex items-center">
                        <i class="fas fa-terminal text-blue-400 mr-3 text-sm"></i> System Log
                    </h3>
                    <div class="space-y-6">
                        @forelse($stats['system_logs'] as $log)
                        <div class="flex items-start space-x-4">
                            <div class="w-2 h-2 rounded-full bg-{{ $log->color }}-400 mt-2 shadow-[0_0_10px_rgba(var(--tw-{{ $log->color }}),0.5)]"></div>
                            <div>
                                <p class="text-xs font-bold text-slate-300">{{ $log->activity }}</p>
                                <p class="text-[10px] text-slate-500 font-medium mt-0.5">{{ $log->description }}</p>
                                <p class="text-[9px] text-slate-600 font-black uppercase tracking-widest mt-1">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center">
                            <p class="text-xs font-bold text-slate-600 uppercase tracking-widest">Belum ada log sistem</p>
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
                <div class="app-surface rounded-[2rem] overflow-hidden p-8 text-center group">
                    <div class="relative inline-block mb-6">
                        <div class="w-28 h-28 rounded-[2rem] bg-blue-500/10 text-blue-400 flex items-center justify-center text-4xl font-black mx-auto border border-blue-500/20 group-hover:scale-105 transition-transform duration-500">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-10 h-10 rounded-2xl bg-slate-900 border-4 border-slate-900 shadow-xl flex items-center justify-center text-blue-400">
                            <i class="fas fa-shield-check text-sm"></i>
                        </div>
                    </div>
                    
                    <h2 class="text-2xl font-black text-slate-100 leading-tight mb-1">{{ $user->name }}</h2>
                    <span class="inline-block px-4 py-1.5 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mb-8 border border-blue-500/15">
                        {{ $user->role }}
                    </span>

                    <div class="space-y-4 pt-8 border-t border-white/5 text-left">
                        <div class="flex justify-between items-center bg-white/[0.03] p-3 rounded-2xl border border-white/5">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">ID RFID</span>
                            <span class="text-xs font-mono font-black text-slate-300">{{ $user->rfid_uid ?? 'Unregistered' }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-white/[0.03] p-3 rounded-2xl border border-white/5">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Username</span>
                            <span class="text-xs font-black text-slate-300">{{ $user->username }}</span>
                        </div>
                    </div>

                    <a href="{{ route('rfid.register.view') }}" class="mt-8 flex items-center justify-center space-x-3 w-full py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-blue-500/10">
                        <i class="fas fa-id-card"></i>
                        <span>Update Kartu</span>
                    </a>
                </div>

                <div class="bg-gradient-to-br from-blue-600/10 to-purple-600/10 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl border border-blue-500/10">
                    <div class="orbital-ring"></div>
                    <i class="fas fa-info-circle absolute -right-4 -top-4 text-7xl opacity-5 rotate-12 text-blue-400"></i>
                    <p class="text-sm font-bold leading-relaxed mb-6 relative z-10 text-slate-300">Selalu pastikan Anda melakukan scan kartu saat datang dan pulang untuk pendataan yang akurat.</p>
                    <div class="bg-white/10 h-1.5 rounded-full overflow-hidden relative z-10">
                        <div class="bg-gradient-to-r from-blue-400 to-purple-400 w-2/3 h-full rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Main Content: Stats & History -->
            <div class="lg:col-span-3 space-y-10">
                @if($user->role === 'guru')
                <div class="app-surface rounded-[2rem] p-10 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 group">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black text-slate-100 mb-2">Panel Guru</h3>
                        <p class="text-slate-400 font-medium max-w-md">Kelola absensi siswa perwalian dan lihat laporan perkembangan belajar.</p>
                    </div>
                    <a href="{{ route('rekap.index') }}" class="relative z-10 flex items-center space-x-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:opacity-90 transition-all shadow-lg shadow-blue-500/10 group-hover:-translate-y-1">
                        <i class="fas fa-chart-pie"></i>
                        <span>Buka Rekap</span>
                    </a>
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-blue-500/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                </div>
                @endif

                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-slate-100 tracking-tight">Statistik Kehadiran</h2>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-white/5 border border-white/10 px-3 py-1 rounded-lg">Bulan Ini</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="app-surface app-card-hover p-6 rounded-[2rem] text-center group hover:border-emerald-500/30 transition-colors">
                            <span class="text-4xl font-black text-emerald-400 leading-none group-hover:scale-110 inline-block transition-transform">{{ $myStats['hadir'] }}</span>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mt-3">Hadir</p>
                        </div>
                        <div class="app-surface app-card-hover p-6 rounded-[2rem] text-center group hover:border-amber-500/30 transition-colors">
                            <span class="text-4xl font-black text-amber-400 leading-none group-hover:scale-110 inline-block transition-transform">{{ $myStats['terlambat'] }}</span>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mt-3">Terlambat</p>
                        </div>
                        <div class="app-surface app-card-hover p-6 rounded-[2rem] text-center group hover:border-blue-500/30 transition-colors">
                            <span class="text-4xl font-black text-blue-400 leading-none group-hover:scale-110 inline-block transition-transform">{{ $myStats['izin'] }}</span>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mt-3">Izin</p>
                        </div>
                        <div class="app-surface app-card-hover p-6 rounded-[2rem] text-center group hover:border-red-500/30 transition-colors">
                            <span class="text-4xl font-black text-red-400 leading-none group-hover:scale-110 inline-block transition-transform">{{ $myStats['alpha'] }}</span>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mt-3">Alpha</p>
                        </div>
                    </div>
                </div>

                <div class="app-surface rounded-[2rem] overflow-hidden">
                    <div class="px-8 py-7 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                        <h3 class="text-xl font-black text-slate-200 tracking-tight">Riwayat Absensi</h3>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Last 5 Activities</span>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            @forelse($myStats['history'] as $h)
                            <div class="flex items-center justify-between p-5 bg-white/[0.02] rounded-2xl border border-white/5 group hover:border-blue-500/20 hover:bg-blue-500/5 transition-all">
                                <div class="flex items-center space-x-5">
                                    <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex flex-col items-center justify-center shadow-sm group-hover:bg-blue-500/10 transition-colors">
                                        <span class="text-[9px] font-black text-slate-500 uppercase leading-none">{{ $h->date->format('M') }}</span>
                                        <span class="text-xl font-black text-slate-200 leading-none mt-1">{{ $h->date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <p class="text-sm font-black text-slate-200">Masuk: {{ $h->time_in }}</p>
                                            <span class="text-[8px] text-slate-600">•</span>
                                            <p class="text-sm font-bold text-slate-500">Pulang: {{ $h->time_out ?? '--:--' }}</p>
                                        </div>
                                        <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em] mt-1.5 flex items-center">
                                            <i class="fas fa-microchip mr-2 text-[10px] text-blue-400"></i> {{ $h->method ?? 'RFID TERMINAL' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $h->status === 'hadir' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                    {{ $h->status }}
                                </span>
                            </div>
                            @empty
                            <div class="py-20 text-center">
                                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center text-slate-600 mx-auto mb-6 text-3xl border border-white/5">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <p class="text-slate-500 font-black uppercase tracking-widest text-xs">Belum ada catatan absensi</p>
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
                    
                    if (e.type === 'in') {
                        if (e.status === 'hadir' && dashHadirToday) {
                            dashHadirToday.textContent = parseInt(dashHadirToday.textContent) + 1;
                        } else if (e.status === 'terlambat' && dashTerlambatToday) {
                            dashTerlambatToday.textContent = parseInt(dashTerlambatToday.textContent) + 1;
                        }
                    }

                    if (dashActivityBody) {
                        const newRow = `
                            <tr class="row-new group hover:bg-white/[0.03] transition-colors">
                                <td class="py-4 px-8">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center font-black text-sm border border-blue-500/20">
                                            ${e.nama_siswa.charAt(0)}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-200 leading-tight">${e.nama_siswa}</span>
                                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">${e.role || 'SISWA'}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm font-bold text-slate-400">${e.waktu_absen}</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest ${e.status === 'hadir' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20'}">
                                        ${e.status}
                                    </span>
                                </td>
                                <td class="py-4 px-8 text-right">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] bg-white/5 px-3 py-1 rounded-lg border border-white/5">${e.metode_rfid || 'RFID'}</span>
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
