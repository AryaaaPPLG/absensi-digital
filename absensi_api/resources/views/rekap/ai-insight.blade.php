<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Overlord Insight - Absensi Digital</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
        .ai-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.1);
        }
        .typing-effect {
            overflow: hidden;
            border-right: .15em solid orange;
            white-space: nowrap;
            margin: 0 auto;
            letter-spacing: .15em;
            animation: 
              typing 3.5s steps(40, end),
              blink-caret .75s step-end infinite;
        }
        @keyframes typing {
          from { width: 0 }
          to { width: 100% }
        }
        @keyframes blink-caret {
          from, to { border-color: transparent }
          50% { border-color: orange; }
        }
        .loading-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-900 font-sans selection:bg-blue-100 selection:text-blue-600 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                        <i class="fas fa-robot text-white text-xl"></i>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-800">AI<span class="text-blue-600">Insight</span></span>
                </a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Dashboard</a>
                <a href="{{ route('rekap.index') }}" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-black uppercase tracking-wider shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all hover:-translate-y-0.5">
                    Rekap
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <section class="relative pt-40 pb-24 overflow-hidden bg-gradient-soft flex-grow flex items-center">
        <div class="hero-shape w-96 h-96 bg-blue-400 opacity-20 -top-20 -right-20"></div>
        <div class="hero-shape w-96 h-96 bg-indigo-400 opacity-20 -bottom-20 -left-20"></div>

        <div class="max-w-4xl mx-auto px-6 w-full">
            <!-- Header Section -->
            <div class="mb-12 text-center">
                <div class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-100 rounded-full space-x-2 mb-6 animate-float">
                    <i class="fas fa-brain text-blue-600 text-xs"></i>
                    <span class="text-xs font-black text-blue-600 uppercase tracking-widest">Powered by Google Gemini Pro</span>
                </div>

                <h1 class="text-5xl lg:text-6xl font-black text-slate-900 leading-[1.2] tracking-tight mb-4">
                    AI Overlord <span class="text-gradient">Insight</span>
                </h1>

                <p class="text-lg text-slate-500 font-medium max-w-2xl mx-auto leading-relaxed">
                    Dapatkan analisis mendalam dan ringkasan otomatis terkait kedisiplinan hari ini menggunakan teknologi Artificial Intelligence terbaru.
                </p>
            </div>

            <!-- AI Insight Card -->
            <div class="ai-card rounded-[2.5rem] shadow-2xl shadow-blue-100 p-8 md:p-12 relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600 opacity-[0.03] rounded-bl-full"></div>
                
                <!-- Action Button -->
                <div class="flex justify-center mb-10">
                    <button id="generateBtn" class="group relative px-8 py-4 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all hover:-translate-y-1 active:scale-95 overflow-hidden">
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-magic mr-3 group-hover:rotate-12 transition-transform"></i>
                            Generate Daily Insight
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </div>

                <!-- Result Area -->
                <div id="resultArea" class="hidden">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-3 gap-4 mb-10">
                        <div class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100 text-center">
                            <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Total Hadir</p>
                            <span id="statTotal" class="text-3xl font-black text-blue-600">0</span>
                        </div>
                        <div class="bg-emerald-50/50 p-6 rounded-3xl border border-emerald-100 text-center">
                            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Tepat Waktu</p>
                            <span id="statHadir" class="text-3xl font-black text-emerald-600">0</span>
                        </div>
                        <div class="bg-amber-50/50 p-6 rounded-3xl border border-amber-100 text-center">
                            <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest mb-1">Terlambat</p>
                            <span id="statTerlambat" class="text-3xl font-black text-amber-600">0</span>
                        </div>
                    </div>

                    <!-- AI Text Content -->
                    <div class="prose prose-slate prose-lg max-w-none">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex-shrink-0 flex items-center justify-center">
                                <i class="fas fa-comment-dots text-blue-600 text-xl"></i>
                            </div>
                            <div id="aiSummary" class="text-slate-700 font-medium leading-relaxed italic">
                                <!-- AI text will appear here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="hidden py-12 text-center">
                    <div class="inline-block relative">
                        <div class="w-20 h-20 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-brain text-blue-600 text-xl loading-pulse"></i>
                        </div>
                    </div>
                    <p class="mt-6 text-sm font-black text-slate-400 uppercase tracking-[0.3em]">AI is thinking...</p>
                </div>

                <!-- Initial State -->
                <div id="initialState" class="py-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-sparkles text-slate-300 text-3xl"></i>
                    </div>
                    <p class="text-slate-400 font-medium">Klik tombol di atas untuk menganalisis data absensi hari ini.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Simple -->
    <footer class="py-12 bg-white border-t border-slate-100 mt-auto">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">
                &copy; 2026 Absensi Digital RFID. Built for excellence.
            </p>
        </div>
    </footer>

    <script>
        const generateBtn = document.getElementById('generateBtn');
        const resultArea = document.getElementById('resultArea');
        const loadingState = document.getElementById('loadingState');
        const initialState = document.getElementById('initialState');
        
        const aiSummary = document.getElementById('aiSummary');
        const statTotal = document.getElementById('statTotal');
        const statHadir = document.getElementById('statHadir');
        const statTerlambat = document.getElementById('statTerlambat');

        generateBtn.addEventListener('click', async () => {
            // Show loading
            generateBtn.disabled = true;
            generateBtn.classList.add('opacity-50', 'cursor-not-allowed');
            initialState.classList.add('hidden');
            resultArea.classList.add('hidden');
            loadingState.classList.remove('hidden');

            try {
                const response = await fetch("{{ route('admin.ai-insight.generate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update content
                    statTotal.textContent = data.data.stats.total;
                    statHadir.textContent = data.data.stats.hadir;
                    statTerlambat.textContent = data.data.stats.terlambat;
                    
                    // Format AI text with line breaks
                    const formattedText = data.data.summary.replace(/\n/g, '<br>');
                    aiSummary.innerHTML = formattedText;

                    // Show result
                    loadingState.classList.add('hidden');
                    resultArea.classList.remove('hidden');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal menghasilkan insight.'
                    });
                    initialState.classList.remove('hidden');
                    loadingState.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server.'
                });
                initialState.classList.remove('hidden');
                loadingState.classList.add('hidden');
            } finally {
                generateBtn.disabled = false;
                generateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    </script>
</body>
</html>
