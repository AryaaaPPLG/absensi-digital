<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Absensi Digital') - Sistem Absensi RFID</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass-nav {
            background: rgba(10, 10, 15, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }
        .text-gradient {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        }
        .swal2-modern-popup {
            border-radius: 28px !important;
            padding: 28px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background: rgba(15, 17, 23, 0.95) !important;
            border: 1px solid rgba(59, 130, 246, 0.15) !important;
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.5), 0 0 40px rgba(59, 130, 246, 0.1) !important;
            backdrop-filter: blur(20px) !important;
        }
        .swal2-modern-title {
            color: #f1f5f9 !important;
            font-size: 1.35rem !important;
            font-weight: 800 !important;
            letter-spacing: -0.01em !important;
        }
        .swal2-modern-html,
        .swal2-modern-popup .swal2-html-container {
            color: #94a3b8 !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            line-height: 1.6 !important;
        }
        .swal2-modern-confirm {
            border-radius: 14px !important;
            padding: 12px 24px !important;
            font-weight: 800 !important;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6) !important;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3) !important;
        }
        .swal2-modern-cancel {
            border-radius: 14px !important;
            padding: 12px 24px !important;
            font-weight: 800 !important;
            background: rgba(51, 65, 85, 0.5) !important;
            color: #94a3b8 !important;
        }
        .swal2-timer-progress-bar {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #10b981) !important;
        }
        .profile-menu {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(0.5rem) scale(0.98);
        }
        .profile-menu.is-open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }
        @media (hover: hover) and (pointer: fine) {
            .profile-menu-wrap:hover .profile-menu {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transform: translateY(0) scale(1);
            }
        }
    </style>
    @yield('styles')
</head>
<body class="antialiased min-h-screen flex flex-col overflow-x-hidden">
    @auth
    <!-- Navbar for Authenticated Users -->
    <nav class="sticky top-0 z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:rotate-6 transition-transform">
                            <i class="fas fa-fingerprint text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-slate-200">Absensi<span class="sv-text-gradient">Digital</span></span>
                    </a>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Realtime Clock -->
                    <div class="hidden md:flex flex-col items-end mr-4 px-4 py-1.5 bg-white/5 border border-white/10 rounded-2xl shadow-sm backdrop-blur-sm">
                        <span id="navClock" class="text-sm font-black text-slate-200 leading-none"></span>
                        <span id="navDate" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-1"></span>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-3 sm:pl-4 sm:border-l sm:border-white/10">
                        <div class="hidden sm:flex flex-col items-end mr-1 max-w-36">
                            <span class="text-sm font-bold text-slate-200">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">{{ Auth::user()->role }}</span>
                        </div>
                        
                        <div class="relative profile-menu-wrap" id="profileMenuWrap">
                            <button
                                type="button"
                                id="profileMenuButton"
                                class="w-11 h-11 sm:w-10 sm:h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-blue-500/10 hover:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 active:scale-95 transition-all border border-white/10"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-controls="profileMenu"
                            >
                                <i class="fas fa-user"></i>
                            </button>
                            <div
                                id="profileMenu"
                                class="profile-menu absolute right-0 top-full z-[60] mt-3 w-56 max-w-[calc(100vw-2rem)] origin-top-right rounded-2xl border border-white/10 bg-slate-900/95 py-2 shadow-xl backdrop-blur-xl transition-all duration-200"
                            >
                                <div class="sm:hidden px-4 py-3 border-b border-white/10">
                                    <span class="block truncate text-sm font-bold text-slate-200">{{ Auth::user()->name }}</span>
                                    <span class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mt-0.5">{{ Auth::user()->role }}</span>
                                </div>
                                <a href="{{ route('dashboard') }}" class="flex min-h-11 items-center px-4 py-2 text-sm font-bold text-slate-300 hover:bg-white/5 hover:text-blue-400 active:bg-white/10">
                                    <i class="fas fa-gauge-high mr-3 text-xs text-slate-500"></i>
                                    Dashboard
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex min-h-11 w-full items-center px-4 py-2 text-left text-sm font-bold text-red-400 hover:bg-red-500/10 active:bg-red-500/20 transition-colors">
                                        <i class="fas fa-arrow-right-from-bracket mr-3 text-xs"></i>
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

    <footer class="bg-black/40 backdrop-blur-xl border-t border-white/5 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-fingerprint text-white text-sm"></i>
                    </div>
                    <span class="text-lg font-extrabold tracking-tight text-slate-300">Absensi<span class="sv-text-gradient">Digital</span></span>
                </div>
                <p class="text-sm font-bold text-slate-600 uppercase tracking-widest">
                    &copy; 2026 Powered by <span class="text-slate-400">RPL SMEMSA (Three Devs)</span>
                </p>
                <div class="flex space-x-6">
                    <a href="https://github.com/aryaaapplg" class="text-slate-600 hover:text-blue-400 transition-colors"><i class="fab fa-github"></i></a>
                    <a href="#" class="text-slate-600 hover:text-blue-400 transition-colors"><i class="fab fa-instagram"></i></a>
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

        const profileMenuWrap = document.getElementById('profileMenuWrap');
        const profileMenuButton = document.getElementById('profileMenuButton');
        const profileMenu = document.getElementById('profileMenu');

        if (profileMenuWrap && profileMenuButton && profileMenu) {
            const setProfileMenuOpen = (isOpen) => {
                profileMenu.classList.toggle('is-open', isOpen);
                profileMenuButton.setAttribute('aria-expanded', String(isOpen));
            };

            profileMenuButton.addEventListener('click', (event) => {
                event.stopPropagation();
                setProfileMenuOpen(!profileMenu.classList.contains('is-open'));
            });

            profileMenu.addEventListener('click', (event) => {
                event.stopPropagation();
            });

            document.addEventListener('click', () => {
                setProfileMenuOpen(false);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    setProfileMenuOpen(false);
                    profileMenuButton.focus();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const AppAlert = window.Swal.mixin({
                buttonsStyling: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#475569',
                showClass: {
                    popup: 'swal2-show'
                },
                hideClass: {
                    popup: 'swal2-hide'
                },
                customClass: {
                    popup: 'swal2-modern-popup',
                    title: 'swal2-modern-title',
                    htmlContainer: 'swal2-modern-html',
                    confirmButton: 'swal2-modern-confirm',
                    cancelButton: 'swal2-modern-cancel'
                }
            });

            @if(session('success'))
                AppAlert.fire({
                    icon: 'success',
                    title: 'Berhasil Disimpan',
                    text: @json(session('success')),
                    showConfirmButton: false,
                    timer: 2600,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                AppAlert.fire({
                    icon: 'error',
                    title: 'Aksi Belum Berhasil',
                    text: @json(session('error')),
                    showConfirmButton: true,
                    confirmButtonText: 'Mengerti'
                });
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>
