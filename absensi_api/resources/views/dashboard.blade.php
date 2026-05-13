<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Absensi Digital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .card-gradient-blue { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .card-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); }
        .card-gradient-purple { background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%); }
        .stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>
<body class="min-h-screen">
    <!-- Sidebar / Nav -->
    <nav class="sticky top-0 z-50 glass border-b border-slate-200 px-6 py-4 mb-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 card-gradient-blue rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <i class="fas fa-fingerprint text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-800">ABSENSI <span class="text-blue-600">DIGITAL</span></h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">RFID Identification System</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-6">
                <div class="hidden md:flex flex-col items-end mr-2">
                    <span class="text-sm font-bold text-slate-700">{{ $user->name }}</span>
                    <span class="text-[10px] font-bold text-blue-500 uppercase">{{ $user->role }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-all duration-300">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 pb-20">
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <i class="fas fa-check-circle mr-3 text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-8 p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-2xl flex items-center shadow-sm">
                <i class="fas fa-info-circle mr-3 text-lg"></i>
                <span class="font-medium">{{ session('info') }}</span>
            </div>
        @endif

        @if($user->role === 'admin')
            <!-- ADMIN DASHBOARD -->
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Ringkasan Admin</h2>
                <p class="text-slate-500">Statistik sistem absensi hari ini, {{ date('d F Y') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="stat-card p-6 rounded-3xl bg-white shadow-sm border border-slate-100 flex flex-col">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <span class="text-slate-500 font-semibold text-sm">Total Pengguna</span>
                    <span class="text-4xl font-black text-slate-800 mt-1">{{ $stats['total_users'] }}</span>
                    <div class="mt-4 flex items-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <span class="text-blue-500 mr-2">{{ $stats['total_guru'] }} Guru</span>
                        <span>&bull;</span>
                        <span class="text-indigo-500 ml-2">{{ $stats['total_siswa'] }} Siswa</span>
                    </div>
                </div>

                <div class="stat-card p-6 rounded-3xl bg-white shadow-sm border border-slate-100">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    <span class="text-slate-500 font-semibold text-sm">Hadir Hari Ini</span>
                    <span class="text-4xl font-black text-slate-800 mt-1">{{ $stats['attendance_today'] }}</span>
                    <div class="mt-4 flex items-center text-[11px] font-bold text-emerald-500 uppercase tracking-wider">
                        <i class="fas fa-arrow-up mr-1"></i> Update Realtime
                    </div>
                </div>

                <div class="stat-card p-6 rounded-3xl bg-white shadow-sm border border-slate-100">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <span class="text-slate-500 font-semibold text-sm">Rata-rata Masuk</span>
                    <span class="text-4xl font-black text-slate-800 mt-1">07:15</span>
                    <div class="mt-4 flex items-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        Zona Waktu WIB
                    </div>
                </div>

                <div class="stat-card p-6 rounded-3xl card-gradient-blue text-white shadow-blue-200">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 text-white flex items-center justify-center mb-4">
                        <i class="fas fa-id-card text-xl"></i>
                    </div>
                    <span class="text-white/70 font-semibold text-sm">Status Server RFID</span>
                    <span class="text-3xl font-black mt-1">OPERASIONAL</span>
                    <div class="mt-4 flex items-center text-[11px] font-bold text-white uppercase tracking-wider">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span> Sistem Aktif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-extrabold text-slate-800">Aktivitas Terbaru</h3>
                        <a href="{{ route('absensi.view') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">Lihat Semua <i class="fas fa-external-link-alt ml-1"></i></a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-slate-400 text-xs font-bold uppercase tracking-widest border-b border-slate-50">
                                    <th class="pb-4 pr-4">Nama</th>
                                    <th class="pb-4 px-4">Waktu</th>
                                    <th class="pb-4 px-4">Status</th>
                                    <th class="pb-4 pl-4 text-right">Metode</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($stats['recent_attendances'] as $att)
                                <tr>
                                    <td class="py-4 pr-4">
                                        <div class="flex items-center">
                                            <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs mr-3">
                                                {{ substr($att->user->name, 0, 1) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-700 leading-tight">{{ $att->user->name }}</span>
                                                <span class="text-[10px] text-slate-400 font-semibold uppercase">{{ $att->user->role }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="text-sm font-semibold text-slate-600">{{ $att->time_in }}</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $att->status === 'hadir' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                            {{ $att->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 pl-4 text-right">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">RFID</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-400 italic">Belum ada data absensi hari ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-indigo-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-100">
                        <div class="relative z-10">
                            <h3 class="text-xl font-extrabold mb-4">Quick Actions</h3>
                            <div class="grid grid-cols-1 gap-3">
                                <a href="{{ route('rekap.index') }}" class="w-full bg-white/10 hover:bg-white/20 p-4 rounded-2xl flex items-center transition-all duration-300">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mr-4">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <span class="font-bold">Rekap Absensi</span>
                                </a>
                                <a href="{{ route('absensi.view') }}" class="w-full bg-white/10 hover:bg-white/20 p-4 rounded-2xl flex items-center transition-all duration-300">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mr-4">
                                        <i class="fas fa-qrcode"></i>
                                    </div>
                                    <span class="font-bold">Buka Terminal Scan</span>
                                </a>
                                <a href="{{ route('users.index') }}" class="w-full bg-white/10 hover:bg-white/20 p-4 rounded-2xl flex items-center transition-all duration-300">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mr-4">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <span class="font-bold">Kelola Pengguna</span>
                                </a>
                            </div>
                        </div>
                        <i class="fas fa-rocket absolute -bottom-10 -right-10 text-[180px] text-white/5 rotate-12"></i>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                        <h3 class="text-lg font-extrabold text-slate-800 mb-6">Device Log</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 rounded-full bg-green-500 mt-1.5"></div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700">Terminal 01 Connected</p>
                                    <p class="text-[10px] text-slate-400 font-semibold uppercase">Today, 07:00 AM</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 rounded-full bg-slate-300 mt-1.5"></div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700">Daily Report Generated</p>
                                    <p class="text-[10px] text-slate-400 font-semibold uppercase">Today, 12:00 AM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- USER DASHBOARD (GURU/SISWA) -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- User Info Profile -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden text-center p-8">
                        <div class="relative inline-block mb-6">
                            <div class="w-24 h-24 rounded-3xl bg-blue-100 text-blue-600 flex items-center justify-center text-4xl font-black mx-auto shadow-inner shadow-blue-200/50">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center text-blue-600">
                                <i class="fas fa-check-circle text-sm"></i>
                            </div>
                        </div>
                        
                        <h2 class="text-2xl font-extrabold text-slate-800 leading-tight mb-1">{{ $user->name }}</h2>
                        <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest mb-6">
                            {{ $user->role }}
                        </span>

                        <div class="space-y-4 pt-6 border-t border-slate-50 text-left">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">ID RFID</span>
                                <span class="text-xs font-mono font-bold text-slate-700">{{ $user->rfid_uid }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Username</span>
                                <span class="text-xs font-bold text-slate-700">{{ $user->username }}</span>
                            </div>
                        </div>

                        <a href="{{ route('rfid.register.view') }}" class="mt-8 block w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-xs font-bold uppercase tracking-widest transition-all">
                            Update Kartu <i class="fas fa-id-card ml-2"></i>
                        </a>
                    </div>

                    <div class="bg-blue-600 rounded-3xl p-6 text-white text-center shadow-lg shadow-blue-200">
                        <i class="fas fa-lightbulb text-3xl mb-4 text-blue-200"></i>
                        <p class="text-sm font-bold leading-relaxed mb-4">Jangan lupa tap kartu saat masuk dan keluar ruangan.</p>
                        <div class="w-full bg-white/20 h-1 rounded-full">
                            <div class="w-1/2 bg-white h-1 rounded-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Statistics & History -->
                <div class="lg:col-span-3 space-y-8">
                    @if($user->role === 'guru')
                    <div class="bg-indigo-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-100 mb-8">
                        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
                            <div>
                                <h3 class="text-2xl font-extrabold mb-2">Panel Guru</h3>
                                <p class="text-indigo-200 text-sm font-medium mb-6 md:mb-0">Kelola absensi siswa dan lihat laporan harian dengan mudah.</p>
                            </div>
                            <a href="{{ route('rekap.index') }}" class="bg-white text-indigo-900 px-6 py-3 rounded-2xl font-bold hover:bg-indigo-50 transition-all shadow-lg">
                                <i class="fas fa-file-alt mr-2"></i> Rekap Absensi
                            </a>
                        </div>
                        <i class="fas fa-chalkboard-teacher absolute -bottom-10 -right-10 text-[150px] text-white/5 rotate-12"></i>
                    </div>
                    @endif

                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-800 mb-6 tracking-tight">Performa Bulan Ini</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
                                <span class="text-3xl font-black text-emerald-500 leading-none">{{ $myStats['hadir'] }}</span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Hadir</p>
                            </div>
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
                                <span class="text-3xl font-black text-amber-500 leading-none">{{ $myStats['terlambat'] }}</span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Terlambat</p>
                            </div>
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
                                <span class="text-3xl font-black text-blue-500 leading-none">{{ $myStats['izin'] }}</span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Izin</p>
                            </div>
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
                                <span class="text-3xl font-black text-red-500 leading-none">{{ $myStats['alpha'] }}</span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Alpha</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Riwayat Absensi Terakhir</h3>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Showing last 5</span>
                        </div>
                        <div class="space-y-4">
                            @forelse($myStats['history'] as $h)
                            <div class="flex items-center justify-between p-5 bg-slate-50 rounded-2xl border border-slate-100/50">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-white flex flex-col items-center justify-center shadow-sm border border-slate-200/50">
                                        <span class="text-[10px] font-black text-slate-400 uppercase leading-none">{{ $h->date->format('M') }}</span>
                                        <span class="text-lg font-black text-slate-800 leading-none mt-0.5">{{ $h->date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700">Masuk pada pukul {{ $h->time_in }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Metode: RFID Card Scan</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-600">
                                    HADIR
                                </span>
                            </div>
                            @empty
                            <div class="py-12 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                <i class="fas fa-clipboard-list text-3xl text-slate-300 mb-3"></i>
                                <p class="text-slate-500 font-medium">Belum ada catatan absensi tersimpan.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <footer class="text-center py-10">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">&copy; 2026 Powered by RFID Tech</p>
    </footer>
</body>
</html>
