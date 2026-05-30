<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Absensi Digital') - Sistem Absensi RFID</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .text-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
    </style>
    @yield('styles')
</head>
<body class="antialiased min-h-screen flex flex-col">
    @auth
    <!-- Navbar for Authenticated Users -->
    <nav class="sticky top-0 z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 group-hover:rotate-6 transition-transform">
                            <i class="fas fa-fingerprint text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-slate-800">Absensi<span class="text-blue-600">Digital</span></span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Realtime Clock -->
                    <div class="hidden md:flex flex-col items-end mr-4 px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-2xl">
                        <span id="navClock" class="text-sm font-black text-slate-800 leading-none"></span>
                        <span id="navDate" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1"></span>
                    </div>

                    <div class="flex items-center space-x-3 pl-4 border-l border-slate-200">
                        <div class="flex flex-col items-end mr-1">
                            <span class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">{{ Auth::user()->role }}</span>
                        </div>
                        
                        <div class="relative group">
                            <button class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition-colors border border-slate-200">
                                <i class="fas fa-user"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right group-hover:translate-y-0 translate-y-2">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600">Dashboard</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center">
                        <i class="fas fa-fingerprint text-white text-sm"></i>
                    </div>
                    <span class="text-lg font-extrabold tracking-tight text-slate-800">Absensi<span class="text-blue-600">Digital</span></span>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">
                    &copy; 2026 Powered by <span class="text-slate-600">RPL SMEMSA</span>
                </p>
                <div class="flex space-x-6">
                    <a href="#" class="text-slate-400 hover:text-blue-600 transition-colors"><i class="fab fa-github"></i></a>
                    <a href="#" class="text-slate-400 hover:text-blue-600 transition-colors"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function updateNavClock() {
            const now = new Date();
            const timeOptions = { 
                hour: '2-digit', minute: '2-digit', second: '2-digit',
                hour12: false, timeZone: 'Asia/Jakarta'
            };
            const dateOptions = { 
                weekday: 'short', day: 'numeric', month: 'short',
                timeZone: 'Asia/Jakarta'
            };
            
            const clockEl = document.getElementById('navClock');
            const dateEl = document.getElementById('navDate');
            
            if (clockEl) clockEl.textContent = now.toLocaleTimeString('id-ID', timeOptions) + ' WIB';
            if (dateEl) dateEl.textContent = now.toLocaleDateString('id-ID', dateOptions);
        }
        
        setInterval(updateNavClock, 1000);
        updateNavClock();

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: { popup: 'rounded-3xl' }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                showConfirmButton: true,
                confirmButtonColor: '#3b82f6',
                customClass: { popup: 'rounded-3xl', confirmButton: 'rounded-xl px-6 py-2' }
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
