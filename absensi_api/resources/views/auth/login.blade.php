<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - Sistem Absensi Digital</title>
  
  <style>
    body { 
        background: #0a0a0f;
        position: relative;
    }
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: -2;
        background-image:
            radial-gradient(circle at 1px 1px, rgba(59, 130, 246, 0.06) 1px, transparent 0);
        background-size: 40px 40px;
        pointer-events: none;
    }
    body::after {
        content: "";
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            radial-gradient(ellipse 500px 350px at 80% 20%, rgba(59, 130, 246, 0.1), transparent),
            radial-gradient(ellipse 400px 300px at 20% 80%, rgba(139, 92, 246, 0.08), transparent);
    }
    .auth-card { 
        background: rgba(15, 17, 23, 0.8); 
        border-radius: 2rem; 
        box-shadow: 0 28px 80px rgba(0, 0, 0, 0.4), 0 0 40px rgba(59, 130, 246, 0.05); 
        border: 1px solid rgba(59, 130, 246, 0.1);
        backdrop-filter: blur(20px);
    }
    .btn-gradient { 
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    }
    .btn-gradient:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.3); 
    }
    .input-focus:focus { 
        border-color: rgba(59, 130, 246, 0.4); 
        outline: none; 
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08), 0 0 20px rgba(59, 130, 246, 0.05); 
        background: rgba(15, 17, 23, 0.8);
    }
    .text-gradient {
        background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
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
        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/20 group-hover:rotate-6 transition-transform">
          <i class="fas fa-fingerprint text-white text-2xl"></i>
        </div>
        <span class="text-2xl font-black tracking-tight text-slate-200">Absensi<span class="text-gradient">Digital</span></span>
      </a>
      <h1 class="text-3xl font-black text-slate-100 tracking-tight">Selamat Datang</h1>
      <p class="text-slate-500 font-medium mt-2 leading-relaxed">Silakan masuk untuk mengakses panel absensi Anda.</p>
    </div>

    <div class="auth-card p-10 md:p-12">
      @if($errors->any())
        <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl text-xs font-black uppercase tracking-widest leading-relaxed">
          <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="space-y-6">
          <div>
            <label for="username" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-1">Username</label>
            <div class="relative group">
              <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500 group-focus-within:text-blue-400 transition-colors">
                <i class="fas fa-user-circle"></i>
              </span>
              <input type="text" id="username" name="username" class="input-focus w-full pl-12 pr-6 py-4 bg-white/5 border border-white/10 rounded-[1.5rem] text-sm font-bold text-slate-200 transition-all placeholder:text-slate-600 placeholder:font-medium" placeholder="Username Anda" required autofocus>
            </div>
          </div>

          <div>
            <div class="flex justify-between items-center mb-3 ml-1">
              <label for="password" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Password</label>
              <a href="#" class="text-[10px] font-black text-blue-400 uppercase tracking-widest hover:underline">Lupa?</a>
            </div>
            <div class="relative group">
              <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-500 group-focus-within:text-blue-400 transition-colors">
                <i class="fas fa-shield-alt"></i>
              </span>
              <input type="password" id="password" name="password" class="input-focus w-full pl-12 pr-6 py-4 bg-white/5 border border-white/10 rounded-[1.5rem] text-sm font-bold text-slate-200 transition-all placeholder:text-slate-600 placeholder:font-medium" placeholder="••••••••" required>
            </div>
          </div>

          <div class="flex items-center space-x-3 ml-1">
            <input type="checkbox" id="remember" class="w-4 h-4 text-blue-500 bg-white/5 border-white/20 rounded focus:ring-blue-500/30">
            <label for="remember" class="text-xs font-bold text-slate-400 uppercase tracking-widest">Ingat Saya</label>
          </div>

          <button type="submit" class="w-full btn-gradient py-4 rounded-[1.5rem] text-white font-black text-xs uppercase tracking-[0.2em] mt-4 flex items-center justify-center space-x-3 group">
            <span>Masuk Sekarang</span>
            <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
          </button>
        </div>
      </form>

      <div class="mt-12 pt-10 border-t border-white/5 text-center">
        <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] mb-4">Belum punya akun?</p>
        <a href="{{ route('register') }}" class="inline-flex items-center space-x-2 text-sm font-black text-blue-400 hover:text-blue-300 transition-all group">
          <span>Daftar Akun Baru</span>
          <i class="fas fa-user-plus text-xs group-hover:scale-110 transition-transform"></i>
        </a>
      </div>
    </div>
    
    <div class="mt-12 text-center">
      <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em]">
        &copy; 2026 Powered by <span class="text-slate-400">RPL SMEMSA</span>
      </p>
    </div>
  </div>
</body>
</html>
