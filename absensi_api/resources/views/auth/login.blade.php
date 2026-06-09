<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - Sistem Absensi Digital</title>
  
  <style>
    body { 
        background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.10), transparent 26rem),
                    radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.08), transparent 24rem),
                    #f8fafc;
    }
    .auth-card { 
        background: rgba(255, 255, 255, 0.9); 
        border-radius: 2rem; 
        box-shadow: 0 28px 80px rgba(15, 23, 42, 0.08); 
        border: 1px solid rgba(226, 232, 240, 0.8);
        backdrop-filter: blur(18px);
    }
    .btn-gradient { 
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    }
    .btn-gradient:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); 
    }
    .input-focus:focus { 
        border-color: #3b82f6; 
        outline: none; 
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.05); 
    }
    .text-gradient {
        background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .auth-shell { animation: auth-rise 500ms ease-out both; }
    @keyframes auth-rise {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-6 antialiased">
  <div class="w-full max-w-md auth-shell">
    <div class="text-center mb-10">
      <a href="/" class="inline-flex items-center space-x-3 mb-8 group">
        <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-200 group-hover:rotate-6 transition-transform">
          <i class="fas fa-fingerprint text-white text-2xl"></i>
        </div>
        <span class="text-2xl font-black tracking-tight text-slate-800">Absensi<span class="text-blue-600">Digital</span></span>
      </a>
      <h1 class="text-3xl font-black text-slate-900 tracking-tight">Selamat Datang</h1>
      <p class="text-slate-500 font-medium mt-2 leading-relaxed">Silakan masuk untuk mengakses panel absensi Anda.</p>
    </div>

    <div class="auth-card p-10 md:p-12">
      @if($errors->any())
        <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-xs font-black uppercase tracking-widest leading-relaxed">
          <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="space-y-6">
          <div>
            <label for="username" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Username</label>
            <div class="relative group">
              <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 group-focus-within:text-blue-600 transition-colors">
                <i class="fas fa-user-circle"></i>
              </span>
              <input type="text" id="username" name="username" class="input-focus w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-[1.5rem] text-sm font-bold text-slate-700 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="Username Anda" required autofocus>
            </div>
          </div>

          <div>
            <div class="flex justify-between items-center mb-3 ml-1">
              <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Password</label>
              <a href="#" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Lupa?</a>
            </div>
            <div class="relative group">
              <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 group-focus-within:text-blue-600 transition-colors">
                <i class="fas fa-shield-alt"></i>
              </span>
              <input type="password" id="password" name="password" class="input-focus w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-[1.5rem] text-sm font-bold text-slate-700 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="••••••••" required>
            </div>
          </div>

          <div class="flex items-center space-x-3 ml-1">
            <input type="checkbox" id="remember" class="w-4 h-4 text-blue-600 bg-slate-50 border-slate-200 rounded focus:ring-blue-500">
            <label for="remember" class="text-xs font-bold text-slate-500 uppercase tracking-widest">Ingat Saya</label>
          </div>

          <button type="submit" class="w-full btn-gradient py-4 rounded-[1.5rem] text-white font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-100 mt-4 flex items-center justify-center space-x-3 group">
            <span>Masuk Sekarang</span>
            <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
          </button>
        </div>
      </form>

      <div class="mt-12 pt-10 border-t border-slate-50 text-center">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Belum punya akun?</p>
        <a href="{{ route('register') }}" class="inline-flex items-center space-x-2 text-sm font-black text-blue-600 hover:text-blue-700 transition-all group">
          <span>Daftar Akun Baru</span>
          <i class="fas fa-user-plus text-xs group-hover:scale-110 transition-transform"></i>
        </a>
      </div>
    </div>
    
    <div class="mt-12 text-center">
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
        &copy; 2026 Powered by <span class="text-slate-600">RPL SMEMSA</span>
      </p>
    </div>
  </div>
</body>
</html>
