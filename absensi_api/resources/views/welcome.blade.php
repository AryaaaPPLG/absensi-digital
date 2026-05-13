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

    <!-- Hero Section -->
    <section class="relative pt-40 pb-24 overflow-hidden bg-gradient-soft flex-grow flex items-center">
        <div class="hero-shape w-96 h-96 bg-blue-400 opacity-20 -top-20 -right-20"></div>
        <div class="hero-shape w-96 h-96 bg-indigo-400 opacity-20 -bottom-20 -left-20"></div>
        
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center w-full">
            <div class="space-y-8 text-center lg:text-left">
                <div class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-100 rounded-full space-x-2">
                    <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                    <span class="text-xs font-black text-blue-600 uppercase tracking-widest">Sistem Absensi Masa Depan</span>
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-black text-slate-900 leading-[1.1] tracking-tight">
                    Presensi Cepat dengan <span class="text-gradient">Teknologi RFID</span>
                </h1>
                
                <p class="text-lg text-slate-500 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Transformasi digital pengelolaan kehadiran tim Anda. Lebih cepat, lebih akurat, dan terintegrasi secara real-time dengan cloud dashboard.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="{{ route('absensi.view') }}" class="w-full sm:w-auto px-10 py-5 bg-blue-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all hover:-translate-y-1">
                        Buka Terminal Absensi
                    </a>
                    <div class="flex -space-x-3">
                        <img src="https://ui-avatars.com/api/?name=User+1&background=random" class="w-12 h-12 rounded-full border-4 border-white">
                        <img src="https://ui-avatars.com/api/?name=User+2&background=random" class="w-12 h-12 rounded-full border-4 border-white">
                        <img src="https://ui-avatars.com/api/?name=User+3&background=random" class="w-12 h-12 rounded-full border-4 border-white">
                        <div class="w-12 h-12 rounded-full bg-blue-50 border-4 border-white flex items-center justify-center">
                            <span class="text-xs font-bold text-blue-600">+1k</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative hidden lg:block">
                <div class="relative z-10 animate-float">
                    <div class="bg-white p-8 rounded-[3rem] shadow-2xl border border-slate-100 max-w-md mx-auto relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[3rem] -z-10"></div>
                        <div class="flex items-center justify-between mb-8">
                            <div class="space-y-1">
                                <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Informasi Kartu</h3>
                                <p class="text-2xl font-black text-blue-600">ID: 102938475</p>
                            </div>
                            <i class="fas fa-wifi text-3xl text-blue-100 rotate-90"></i>
                        </div>
                        <div class="aspect-square bg-slate-50 rounded-3xl mb-8 flex items-center justify-center border-2 border-dashed border-slate-200">
                            <i class="fas fa-id-card text-8xl text-slate-200"></i>
                        </div>
                        <div class="space-y-4">
                            <div class="h-4 bg-slate-100 rounded-full w-full"></div>
                            <div class="h-4 bg-slate-100 rounded-full w-3/4"></div>
                            <button class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest">Scan RFID Card</button>
                        </div>
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

</body>
</html>
