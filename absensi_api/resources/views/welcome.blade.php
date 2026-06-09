@extends('layouts.app')

@section('title', 'Monitor Kehadiran Real-time')

@section('styles')
<style>
    .text-gradient {
        background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .status-badge {
        transition: all 0.3s ease;
    }
    .table-container {
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 table-container">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="max-w-2xl">
            <div class="inline-flex items-center px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full space-x-2 mb-4">
                <span class="flex h-2 w-2 rounded-full bg-blue-400 animate-pulse"></span>
                <span class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em]">Live Monitoring System</span>
            </div>
            <h1 class="text-4xl font-black text-slate-100 tracking-tight">Monitor Kehadiran <span class="text-gradient">Real-time</span></h1>
            <p class="text-slate-400 font-medium mt-2">Daftar aktivitas kehadiran siswa hari ini secara langsung dari terminal RFID.</p>
        </div>
        
        <div class="app-surface p-6 rounded-[2rem] text-center min-w-[240px]">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Waktu Saat Ini</p>
            <p id="mainClock" class="text-3xl font-black sv-text-gradient tracking-tight">--:--:--</p>
            <p id="mainDate" class="text-xs font-bold text-slate-500 mt-1">-------</p>
        </div>
    </div>

    <div class="app-surface rounded-[2.5rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/[0.02]">
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">No</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Siswa</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Kelas / Jurusan</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Waktu</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-right text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5" id="liveAttendanceBody">
                    <tr>
                        <td colspan="6" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 border-4 border-blue-500/20 border-t-blue-400 rounded-full animate-spin mb-4"></div>
                                <p class="text-slate-500 font-black uppercase tracking-widest text-xs">Menghubungkan ke server...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 bg-white/[0.02] border-t border-white/5 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-3">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-400"></span>
                </span>
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Sistem Monitor Aktif</span>
            </div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                Total Hari Ini: <span class="text-blue-400 font-black" id="totalCount">0</span> Siswa
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-6">
        @guest
        <a href="{{ route('login') }}" class="group app-surface app-card-hover p-6 rounded-[2rem] flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-500/10 text-blue-400 rounded-2xl flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-all border border-blue-500/20">
                <i class="fas fa-sign-in-alt text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Akses Akun</p>
                <p class="text-sm font-black text-slate-200 tracking-tight">Masuk ke Dashboard</p>
            </div>
        </a>
        <a href="{{ route('register') }}" class="group app-surface app-card-hover p-6 rounded-[2rem] flex items-center space-x-4">
            <div class="w-12 h-12 bg-purple-500/10 text-purple-400 rounded-2xl flex items-center justify-center group-hover:bg-purple-500 group-hover:text-white transition-all border border-purple-500/20">
                <i class="fas fa-user-plus text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Belum Punya Akun?</p>
                <p class="text-sm font-black text-slate-200 tracking-tight">Daftar Sekarang</p>
            </div>
        </a>
        @endguest
        <a href="{{ route('absensi.view') }}" class="group bg-gradient-to-br from-slate-800 to-slate-900 p-6 rounded-[2rem] shadow-xl shadow-blue-500/5 flex items-center space-x-4 border border-white/5 hover:border-blue-500/20 transition-all">
            <div class="w-12 h-12 bg-blue-500/10 text-blue-400 rounded-2xl flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-all border border-blue-500/20">
                <i class="fas fa-qrcode text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Terminal Scanner</p>
                <p class="text-sm font-black text-slate-100 tracking-tight">Buka Mode Absensi</p>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const liveAttendanceBody = document.getElementById('liveAttendanceBody');
    const totalCount = document.getElementById('totalCount');
    const mainClock = document.getElementById('mainClock');
    const mainDate = document.getElementById('mainDate');

    function updateLocalClock() {
        const now = new Date();
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' };
        const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' };
        if (mainClock) mainClock.textContent = now.toLocaleTimeString('id-ID', timeOptions) + ' WIB';
        if (mainDate) mainDate.textContent = now.toLocaleDateString('id-ID', dateOptions);
    }
    
    setInterval(updateLocalClock, 1000);
    updateLocalClock();

    async function fetchLatestAttendance() {
        try {
            const response = await fetch('/api/attendance/realtime');
            const data = await response.json();
            if (data.success) {
                renderTable(data.recent);
            }
        } catch (error) {
            console.error('Error fetching attendance:', error);
        }
    }

    function renderTable(data) {
        if (data.length === 0) {
            liveAttendanceBody.innerHTML = '<tr><td colspan="6" class="px-8 py-20 text-center text-slate-500 font-bold uppercase tracking-widest text-xs italic">Belum ada aktivitas hari ini</td></tr>';
            totalCount.textContent = '0';
            return;
        }

        totalCount.textContent = data.length;
        let html = '';
        data.forEach((item, index) => {
            const statusClass = getStatusStyles(item.status);
            html += `
                <tr class="group hover:bg-white/[0.03] transition-all duration-300">
                    <td class="px-8 py-5 text-sm font-black text-slate-300">${index + 1}</td>
                    <td class="px-8 py-5">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center font-black text-slate-400 group-hover:bg-blue-500/20 group-hover:text-blue-400 transition-all border border-white/5">
                                ${item.user_name.charAt(0)}
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-200">${item.user_name}</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">ID: #${item.user_id}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-sm font-bold text-slate-400">${item.kelas || '-'}</span>
                        <span class="text-xs text-slate-600 mx-2">/</span>
                        <span class="text-sm font-bold text-slate-400">${item.jurusan || '-'}</span>
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-slate-400">${item.time_in}</td>
                    <td class="px-8 py-5">
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest ${statusClass} status-badge">
                            <i class="fas ${getStatusIcon(item.status)} mr-2"></i> ${item.status}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-white/5 px-3 py-1.5 rounded-lg border border-white/5">${item.method}</span>
                    </td>
                </tr>
            `;
        });
        liveAttendanceBody.innerHTML = html;
    }

    function getStatusStyles(status) {
        switch(status.toLowerCase()) {
            case 'hadir': return 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
            case 'terlambat': return 'bg-amber-500/10 text-amber-400 border border-amber-500/20';
            case 'izin': return 'bg-blue-500/10 text-blue-400 border border-blue-500/20';
            default: return 'bg-white/5 text-slate-400 border border-white/10';
        }
    }

    function getStatusIcon(status) {
        switch(status.toLowerCase()) {
            case 'hadir': return 'fa-check-circle';
            case 'terlambat': return 'fa-clock';
            case 'izin': return 'fa-file-alt';
            default: return 'fa-question-circle';
        }
    }

    // Initial fetch
    fetchLatestAttendance();
    // Poll every 10 seconds
    setInterval(fetchLatestAttendance, 10000);
</script>
@endpush
