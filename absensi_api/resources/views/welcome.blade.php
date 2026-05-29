<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Digital RFID</title>
    
    
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .text-gradient {
            background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-gradient-soft {
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.05), transparent),
                        radial-gradient(circle at bottom left, rgba(37, 99, 235, 0.05), transparent);
        }
        .hero-shape {
            position: absolute;
            z-index: -1;
            filter: blur(80px);
            border-radius: 50%;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .row-update {
            animation: highlight 2s ease-out;
        }
        @keyframes highlight {
            0% { background-color: rgba(59, 130, 246, 0.2); }
            100% { background-color: transparent; }
        }
    </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans selection:bg-blue-100 selection:text-blue-600 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <i class="fas fa-fingerprint text-white text-xl"></i>
                </div>
                <span class="text-xl font-extrabold tracking-tight text-slate-800">Absensi<span class="text-blue-600">Digital</span></span>
            </div>

            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-black uppercase tracking-wider shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all hover:-translate-y-0.5">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-black uppercase tracking-wider shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all hover:-translate-y-0.5">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Attendance Table Section -->
    <section class="relative pt-40 pb-24 overflow-hidden bg-gradient-soft flex-grow flex items-center">
        <div class="hero-shape w-96 h-96 bg-blue-400 opacity-20 -top-20 -right-20"></div>
        <div class="hero-shape w-96 h-96 bg-indigo-400 opacity-20 -bottom-20 -left-20"></div>

        <div class="max-w-7xl mx-auto px-6 w-full">
            <!-- Header Section -->
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <div class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-100 rounded-full space-x-2 mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                        <span class="text-xs font-black text-blue-600 uppercase tracking-widest">Data Kehadiran Real-Time</span>
                    </div>

                    <h1 class="text-5xl lg:text-6xl font-black text-slate-900 leading-[1.2] tracking-tight mb-4">
                        Daftar <span class="text-gradient">Kehadiran Siswa</span>
                    </h1>

                    <p class="text-lg text-slate-500 font-medium max-w-2xl leading-relaxed">
                        Pantau kehadiran tim Anda secara real-time. Sistem otomatis mencatat setiap absensi melalui teknologi RFID yang akurat dan terintegrasi.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100 min-w-[300px] text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Waktu Saat Ini (WIB)</p>
                    <div id="realtimeClock" class="text-3xl font-black text-blue-600 tracking-tight"></div>
                    <p id="realtimeDate" class="text-sm font-bold text-slate-500 mt-1"></p>
                </div>
            </div>

            <!-- Attendance Table Card -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <!-- Table Header with Action -->
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <h2 class="text-xl font-black text-slate-800 flex items-center space-x-3">
                        <i class="fas fa-table text-blue-600"></i>
                        <span>Urutan Kehadiran Hari Ini</span>
                    </h2>
                    <a href="{{ route('absensi.view') }}" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-black uppercase tracking-wider shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all hover:-translate-y-0.5">
                        <i class="fas fa-arrow-right mr-2"></i>Terminal
                    </a>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50">
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">No</th>
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Nama Siswa</th>
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Kelas</th>
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Jurusan</th>
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Tanggal</th>
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Waktu Masuk</th>
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Metode</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="attendanceBody">
                            <!-- Data will be loaded via JS -->
                            <tr>
                                <td colspan="8" class="px-8 py-10 text-center text-slate-400 italic">
                                    Menghubungkan ke server...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                    <p class="text-sm text-slate-600 font-medium">
                        <span class="font-bold text-slate-900" id="totalHadir">0</span> Data kehadiran terbaru ditampilkan
                    </p>
                    <div class="text-xs font-bold text-blue-600 uppercase tracking-widest flex items-center">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mr-2 animate-pulse"></div>
                        Update Real-time Aktif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Simple -->
    <footer class="py-12 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">
                &copy; 2026 Absensi Digital RFID. Built for excellence.
            </p>
        </div>
    </footer>

    <script>
        const attendanceBody = document.getElementById('attendanceBody');
        const totalHadir = document.getElementById('totalHadir');
        const realtimeClock = document.getElementById('realtimeClock');
        const realtimeDate = document.getElementById('realtimeDate');

        function updateClock() {
            const now = new Date();
            const timeOptions = { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            };
            const dateOptions = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                timeZone: 'Asia/Jakarta'
            };
            realtimeClock.textContent = now.toLocaleTimeString('id-ID', timeOptions);
            realtimeDate.textContent = now.toLocaleDateString('id-ID', dateOptions);
        }
        
        setInterval(updateClock, 1000);
        updateClock();

        let lastDataIds = [];

        async function fetchRealtimeData() {
            try {
                const response = await fetch('/api/attendance/realtime');
                const data = await response.json();

                if (data.success) {
                    updateTable(data.recent);
                }
            } catch (error) {
                console.error('Error fetching realtime data:', error);
            }
        }

        function updateTable(recent) {
            if (recent.length === 0) {
                attendanceBody.innerHTML = '<tr><td colspan="8" class="px-8 py-10 text-center text-slate-400 italic">Belum ada data kehadiran hari ini</td></tr>';
                totalHadir.textContent = '0';
                return;
            }

            totalHadir.textContent = recent.length;
            
            // Check if there are new items to highlight
            const currentIds = recent.map(item => item.id);
            const newIds = currentIds.filter(id => !lastDataIds.includes(id));
            
            let html = '';
            recent.forEach((item, index) => {
                const isNew = newIds.includes(item.id);
                const statusClass = getStatusClass(item.status);
                
                html += `
                    <tr class="hover:bg-slate-50 transition-colors ${isNew ? 'row-update' : ''}">
                        <td class="px-8 py-5 text-sm font-bold text-slate-900">${index + 1}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center space-x-3">
                                <img src="${item.avatar}" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">${item.user_name}</p>
                                    <p class="text-xs text-slate-500">ID: #${item.user_id}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-600 font-bold">${item.kelas || '-'}</td>
                        <td class="px-8 py-5 text-sm text-slate-600 font-bold">${item.jurusan || '-'}</td>
                        <td class="px-8 py-5 text-sm text-slate-600 font-medium">${item.date}</td>
                        <td class="px-8 py-5 text-sm text-slate-600 font-medium">${item.time_in || '-'}</td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold ${statusClass}">
                                <i class="fas ${getStatusIcon(item.status)} mr-2"></i> ${capitalize(item.status)}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                <i class="fas ${getMethodIcon(item.method)} mr-2"></i> ${item.method}
                            </span>
                        </td>
                    </tr>
                `;
            });

            attendanceBody.innerHTML = html;
            lastDataIds = currentIds;
        }

        function getStatusClass(status) {
            switch(status.toLowerCase()) {
                case 'hadir': return 'bg-emerald-100 text-emerald-700';
                case 'terlambat': return 'bg-amber-100 text-amber-700';
                case 'izin': return 'bg-cyan-100 text-cyan-700';
                case 'alpha': return 'bg-red-100 text-red-700';
                default: return 'bg-slate-100 text-slate-700';
            }
        }

        function getStatusIcon(status) {
            switch(status.toLowerCase()) {
                case 'hadir': return 'fa-check-circle';
                case 'terlambat': return 'fa-clock';
                case 'izin': return 'fa-file-alt';
                case 'alpha': return 'fa-times-circle';
                default: return 'fa-question-circle';
            }
        }

        function getMethodIcon(method) {
            return 'fa-id-card';
        }

        function capitalize(s) {
            return s.charAt(0).toUpperCase() + s.slice(1);
        }

        // Initial fetch
        fetchRealtimeData();

        // Poll every 5 seconds
        setInterval(fetchRealtimeData, 5000);
    </script>
</body>
</html>
