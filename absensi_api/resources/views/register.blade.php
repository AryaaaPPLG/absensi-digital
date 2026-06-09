<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar - Sistem Absensi Digital</title>
  
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
            radial-gradient(ellipse 500px 350px at 20% 20%, rgba(139, 92, 246, 0.08), transparent),
            radial-gradient(ellipse 400px 300px at 80% 80%, rgba(59, 130, 246, 0.08), transparent);
    }
    .auth-card { 
        background: rgba(15, 17, 23, 0.8); 
        border-radius: 2rem; 
        box-shadow: 0 28px 80px rgba(0, 0, 0, 0.4), 0 0 40px rgba(139, 92, 246, 0.05); 
        border: 1px solid rgba(59, 130, 246, 0.1);
        backdrop-filter: blur(20px);
    }
    .btn-gradient { 
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    }
    .btn-gradient:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 0 30px rgba(139, 92, 246, 0.3); 
    }
    .input-focus:focus { 
        border-color: rgba(59, 130, 246, 0.4); 
        outline: none; 
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08); 
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
  <div class="w-full max-w-2xl auth-shell">
    <div class="text-center mb-10">
      <a href="/" class="inline-flex items-center space-x-3 mb-8 group">
        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/20 group-hover:rotate-6 transition-transform">
          <i class="fas fa-fingerprint text-white text-2xl"></i>
        </div>
        <span class="text-2xl font-black tracking-tight text-slate-200">Absensi<span class="text-gradient">Digital</span></span>
      </a>
      <h1 class="text-3xl font-black text-slate-100 tracking-tight">Buat Akun Baru</h1>
      <p class="text-slate-500 font-medium mt-2 leading-relaxed">Lengkapi formulir di bawah untuk mendaftarkan akun Anda.</p>
    </div>

    <div class="auth-card p-8 md:p-12">
      @if($errors->any())
        <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl text-xs font-black uppercase tracking-widest leading-relaxed">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="md:col-span-2">
                <h3 class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-4 flex items-center">
                    <span class="w-8 h-[1px] bg-blue-400/20 mr-3"></span> Informasi Dasar
                </h3>
            </div>
            
            <div>
              <label for="username" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-1">Username</label>
              <input type="text" id="username" name="username" value="{{ old('username') }}" class="input-focus w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-sm font-bold text-slate-200 transition-all placeholder:text-slate-600 placeholder:font-medium" placeholder="Username" required autofocus>
            </div>

            <div>
              <label for="name" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-1">Nama Lengkap</label>
              <input type="text" id="name" name="name" value="{{ old('name') }}" class="input-focus w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-sm font-bold text-slate-200 transition-all placeholder:text-slate-600 placeholder:font-medium" placeholder="Nama Lengkap" required>
            </div>

            <div class="md:col-span-2">
              <label for="email" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-1">Email</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" class="input-focus w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-sm font-bold text-slate-200 transition-all placeholder:text-slate-600 placeholder:font-medium" placeholder="nama@email.com" required>
            </div>

            <div>
              <label for="role" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-1">Peran</label>
              <select id="role" name="role" class="input-focus w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-sm font-bold text-slate-200 transition-all appearance-none" required>
                <option value="">Pilih Peran</option>
                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
              </select>
            </div>

            <div>
              <label for="kelas" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-1">Kelas / Jurusan</label>
              <div class="grid grid-cols-2 gap-2">
                <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}" class="input-focus w-full px-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-sm font-bold text-slate-200 transition-all placeholder:text-slate-600" placeholder="Kelas">
                <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan') }}" class="input-focus w-full px-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-sm font-bold text-slate-200 transition-all placeholder:text-slate-600" placeholder="Jurusan">
              </div>
            </div>

            <div class="md:col-span-2 mt-4">
                <h3 class="text-[10px] font-black text-purple-400 uppercase tracking-[0.2em] mb-4 flex items-center">
                    <span class="w-8 h-[1px] bg-purple-400/20 mr-3"></span> Keamanan Akun
                </h3>
            </div>

            <div>
              <label for="password" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-1">Password</label>
              <input type="password" id="password" name="password" class="input-focus w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-sm font-bold text-slate-200 transition-all placeholder:text-slate-600 placeholder:font-medium" placeholder="••••••••" required>
            </div>

            <div>
              <label for="password_confirmation" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-1">Konfirmasi Password</label>
              <input type="password" id="password_confirmation" name="password_confirmation" class="input-focus w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-sm font-bold text-slate-200 transition-all placeholder:text-slate-600 placeholder:font-medium" placeholder="••••••••" required>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4 mt-10">
          <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-slate-300 transition-colors text-center">
            Sudah Punya Akun?
          </a>
          <button type="submit" class="w-full sm:flex-1 btn-gradient py-4 rounded-2xl text-white font-black text-xs uppercase tracking-[0.2em] flex items-center justify-center space-x-3 group">
            <span>Daftar Akun Baru</span>
            <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
          </button>
        </div>
      </form>
    </div>
    
    <div class="mt-12 text-center">
      <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em]">
        &copy; 2026 Powered by <span class="text-slate-400">RPL SMEMSA</span>
      </p>
    </div>
  </div>
</body>
</html>
