<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Digital RFID</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
    </style>
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
            <div class="mb-12">
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
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Tanggal</th>
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Waktu Masuk</th>
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-widest">Metode</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <!-- Data 1 -->
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5 text-sm font-bold text-slate-900">1</td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=3b82f6&color=fff" class="w-10 h-10 rounded-full">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">Budi Santoso</p>
                                            <p class="text-xs text-slate-500">ID: #001</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-slate-600 font-medium">13 Mei 2026</td>
                                <td class="px-8 py-5 text-sm text-slate-600 font-medium">08:15</td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                        <i class="fas fa-check-circle mr-2"></i> Hadir
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                        <i class="fas fa-id-card mr-2"></i> RFID
                                    </span>
                                </td>
                            </tr>

                            <!-- Data 2 -->
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5 text-sm font-bold text-slate-900">2</td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name=Siti+Nurhaliza&background=3b82f6&color=fff" class="w-10 h-10 rounded-full">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">Siti Nurhaliza</p>
                                            <p class="text-xs text-slate-500">ID: #002</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-slate-600 font-medium">13 Mei 2026</td>
                                <td class="px-8 py-5 text-sm text-slate-600 font-medium">08:32</td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                        <i class="fas fa-clock mr-2"></i> Terlambat
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                                        <i class="fas fa-face-smile mr-2"></i> Face
                                    </span>
                                </td>
                            </tr>

                            <!-- Data 3 -->
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5 text-sm font-bold text-slate-900">3</td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name=Ahmad+Hidayat&background=3b82f6&color=fff" class="w-10 h-10 rounded-full">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">Ahmad Hidayat</p>
                                            <p class="text-xs text-slate-500">ID: #003</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-slate-600 font-medium">13 Mei 2026</td>
                                <td class="px-8 py-5 text-sm text-slate-600 font-medium">-</td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                        <i class="fas fa-times-circle mr-2"></i> Alpha
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                        <i class="fas fa-minus-circle mr-2"></i> -
                                    </span>
                                </td>
                            </tr>

                            <!-- Data 4 -->
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5 text-sm font-bold text-slate-900">4</td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name=Rina+Wijaya&background=3b82f6&color=fff" class="w-10 h-10 rounded-full">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">Rina Wijaya</p>
                                            <p class="text-xs text-slate-500">ID: #004</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-slate-600 font-medium">13 Mei 2026</td>
                                <td class="px-8 py-5 text-sm text-slate-600 font-medium">07:45</td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-cyan-100 text-cyan-700">
                                        <i class="fas fa-file-alt mr-2"></i> Izin
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-pink-100 text-pink-700">
                                        <i class="fas fa-qrcode mr-2"></i> QR Code
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                    <p class="text-sm text-slate-600 font-medium">
                        <span class="font-bold text-slate-900">4</span> Data kehadiran ditampilkan dari total Siswa
                    </p>
                    <div class="flex items-center space-x-3">
                        <button class="px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-colors">← Sebelumnya</button>
                        <div class="flex items-center space-x-1">
                            <button class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-sm">1</button>
                            <button class="w-8 h-8 rounded-lg text-slate-600 hover:bg-white font-bold text-sm">2</button>
                            <button class="w-8 h-8 rounded-lg text-slate-600 hover:bg-white font-bold text-sm">3</button>
                        </div>
                        <button class="px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-colors">Selanjutnya →</button>
                    </div>
                </div>
            </div>

            <!-- Info Stats Row -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
                <div class="bg-white rounded-2xl p-6 border border-slate-100 text-center hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-xl mb-4">
                        <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                    </div>
                    <p class="text-sm text-slate-600 font-medium mb-1">Hadir</p>
                    <p class="text-3xl font-black text-slate-900">1</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100 text-center hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-amber-100 rounded-xl mb-4">
                        <i class="fas fa-clock text-amber-600 text-xl"></i>
                    </div>
                    <p class="text-sm text-slate-600 font-medium mb-1">Terlambat</p>
                    <p class="text-3xl font-black text-slate-900">1</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100 text-center hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-cyan-100 rounded-xl mb-4">
                        <i class="fas fa-file-alt text-cyan-600 text-xl"></i>
                    </div>
                    <p class="text-sm text-slate-600 font-medium mb-1">Izin</p>
                    <p class="text-3xl font-black text-slate-900">1</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100 text-center hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-xl mb-4">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <p class="text-sm text-slate-600 font-medium mb-1">Alpha</p>
                    <p class="text-3xl font-black text-slate-900">1</p>
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

</body>
</html>
