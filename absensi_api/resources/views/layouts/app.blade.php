<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
     <!-- * @KISI-KISI: BLADE TEMPLATING (yield)
     * yield digunakan sebagai placeholder konten yang akan diisi oleh child view.
     */ -->
    <title>@yield('title', 'Absensi Digital') - Sistem Absensi RFID</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.75);
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.04);
        }
        .text-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .swal2-modern-popup {
            border-radius: 28px !important;
            padding: 28px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.18) !important;
        }
        .swal2-modern-title {
            color: #0f172a !important;
            font-size: 1.35rem !important;
            font-weight: 800 !important;
            letter-spacing: -0.01em !important;
        }
        .swal2-modern-html,
        .swal2-modern-popup .swal2-html-container {
            color: #64748b !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            line-height: 1.6 !important;
        }
        .swal2-modern-confirm {
            border-radius: 14px !important;
            padding: 12px 24px !important;
            font-weight: 800 !important;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.28) !important;
        }
        .swal2-modern-cancel {
            border-radius: 14px !important;
            padding: 12px 24px !important;
            font-weight: 800 !important;
        }
        .swal2-timer-progress-bar {
            background: linear-gradient(90deg, #2563eb, #22c55e) !important;
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
    <div class="pointer-events-none fixed inset-0 -z-10 opacity-80">
        <div class="absolute left-1/2 top-0 h-96 w-[44rem] -translate-x-1/2 rounded-full bg-blue-100/60 blur-3xl"></div>
        <div class="absolute bottom-20 right-0 h-80 w-80 rounded-full bg-emerald-100/50 blur-3xl"></div>
    </div>
    @auth
    <!-- Navbar for Authenticated Users -->
    <nav class="sticky top-0 z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg shadow-slate-200 group-hover:rotate-6 transition-transform">
                            <i class="fas fa-fingerprint text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-slate-800">Absensi<span class="text-blue-600">Digital</span></span>
                    </a>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Realtime Clock -->
                    <div class="hidden md:flex flex-col items-end mr-4 px-4 py-1.5 bg-white/70 border border-slate-200 rounded-2xl shadow-sm">
                        <span id="navClock" class="text-sm font-black text-slate-800 leading-none"></span>
                        <span id="navDate" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1"></span>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-3 sm:pl-4 sm:border-l sm:border-slate-200">
                        <div class="hidden sm:flex flex-col items-end mr-1 max-w-36">
                            <span class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">{{ Auth::user()->role }}</span>
                        </div>
                        
                        <div class="relative profile-menu-wrap" id="profileMenuWrap">
                            <button
                                type="button"
                                id="profileMenuButton"
                                class="w-11 h-11 sm:w-10 sm:h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-blue-50 hover:text-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-100 active:scale-95 transition-all border border-slate-200"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-controls="profileMenu"
                            >
                                <i class="fas fa-user"></i>
                            </button>
                            <div
                                id="profileMenu"
                                class="profile-menu absolute right-0 top-full z-[60] mt-3 w-56 max-w-[calc(100vw-2rem)] origin-top-right rounded-2xl border border-slate-100 bg-white py-2 shadow-xl transition-all duration-200"
                            >
                                <div class="sm:hidden px-4 py-3 border-b border-slate-100">
                                    <span class="block truncate text-sm font-bold text-slate-800">{{ Auth::user()->name }}</span>
                                    <span class="block text-[10px] font-black text-blue-600 uppercase tracking-widest mt-0.5">{{ Auth::user()->role }}</span>
                                </div>
                                <a href="{{ route('dashboard') }}" class="flex min-h-11 items-center px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-blue-600 active:bg-slate-100">
                                    <i class="fas fa-gauge-high mr-3 text-xs text-slate-400"></i>
                                    Dashboard
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    <!-- /**
                                     * KISI-KISI: KEAMANAN SISTEM (csrf)
                                     * Direktif csrf akan menghasilkan input hidden berisi token keamanan.
                                     */ -->
                                    @csrf
                                    <button type="submit" class="flex min-h-11 w-full items-center px-4 py-2 text-left text-sm font-bold text-red-600 hover:bg-red-50 active:bg-red-100 transition-colors">
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
        <!-- /**
         * KISI-KISI: BLADE TEMPLATING (yield)
         * Menampilkan konten utama halaman.
         */ -->
        @yield('content')
    </main>

    <footer class="bg-white/80 backdrop-blur border-t border-slate-200 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center">
                        <i class="fas fa-fingerprint text-white text-sm"></i>
                    </div>
                    <span class="text-lg font-extrabold tracking-tight text-slate-800">Absensi<span class="text-blue-600">Digital</span></span>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">
                    &copy; 2026 Powered by <span class="text-slate-600">RPL SMEMSA (Three Devs)</span>
                </p>
                <div class="flex space-x-6">
                    <a href="https://github.com/aryaaapplg" class="text-slate-400 hover:text-blue-600 transition-colors"><i class="fab fa-github"></i></a>
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
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#94a3b8',
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
