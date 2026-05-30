<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar - Sistem Absensi Digital</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.05), transparent),
                    radial-gradient(circle at bottom left, rgba(37, 99, 235, 0.05), transparent),
                    #f8fafc;
    }
    .auth-card { 
        background: white; 
        border-radius: 3rem; 
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05); 
        border: 1px solid rgba(226, 232, 240, 0.5);
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
  </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-6 antialiased">
  <div class="w-full max-w-2xl">
    <div class="text-center mb-10">
      <a href="/" class="inline-flex items-center space-x-3 mb-8 group">
        <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-200 group-hover:rotate-6 transition-transform">
          <i class="fas fa-fingerprint text-white text-2xl"></i>
        </div>
        <span class="text-2xl font-black tracking-tight text-slate-800">Absensi<span class="text-blue-600">Digital</span></span>
      </a>
      <h1 class="text-3xl font-black text-slate-900 tracking-tight">Buat Akun Baru</h1>
      <p class="text-slate-500 font-medium mt-2 leading-relaxed">Lengkapi formulir di bawah untuk mendaftarkan akun Anda.</p>
    </div>

    <div class="auth-card p-8 md:p-12">
      @if($errors->any())
        <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-xs font-black uppercase tracking-widest leading-relaxed">
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
                <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-4 flex items-center">
                    <span class="w-8 h-[1px] bg-blue-600/20 mr-3"></span> Informasi Dasar
                </h3>
            </div>
            
            <div>
              <label for="username" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Username</label>
              <input type="text" id="username" name="username" value="{{ old('username') }}" class="input-focus w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="Username" required autofocus>
            </div>

            <div>
              <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Nama Lengkap</label>
              <input type="text" id="name" name="name" value="{{ old('name') }}" class="input-focus w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="Nama Lengkap" required>
            </div>

            <div class="md:col-span-2">
              <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Email</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" class="input-focus w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="nama@email.com" required>
            </div>

            <div>
              <label for="role" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Peran</label>
              <select id="role" name="role" class="input-focus w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 transition-all appearance-none" required>
                <option value="">Pilih Peran</option>
                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
              </select>
            </div>

            <div>
              <label for="kelas" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Kelas / Jurusan</label>
              <div class="grid grid-cols-2 gap-2">
                <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}" class="input-focus w-full px-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 transition-all" placeholder="Kelas">
                <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan') }}" class="input-focus w-full px-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 transition-all" placeholder="Jurusan">
              </div>
            </div>

            <div class="md:col-span-2 mt-4">
                <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-4 flex items-center">
                    <span class="w-8 h-[1px] bg-blue-600/20 mr-3"></span> Keamanan Akun
                </h3>
            </div>

            <div>
              <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Password</label>
              <input type="password" id="password" name="password" class="input-focus w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="••••••••" required>
            </div>

            <div>
              <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Konfirmasi Password</label>
              <input type="password" id="password_confirmation" name="password_confirmation" class="input-focus w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="••••••••" required>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4 mt-10">
          <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors text-center">
            Sudah Punya Akun?
          </a>
          <button type="submit" class="w-full sm:flex-1 btn-gradient py-4 rounded-2xl text-white font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-100 flex items-center justify-center space-x-3 group">
            <span>Daftar Akun Baru</span>
            <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
          </button>
        </div>
      </form>
    </div>
    
    <div class="mt-12 text-center">
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
        &copy; 2026 Powered by <span class="text-slate-600">RPL SMEMSA</span>
      </p>
    </div>
  </div>
</body>
</html>
