<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Absensi Digital</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }
    .auth-card { background: white; border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08); }
    .btn-gradient { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); transition: all 0.3s ease; }
    .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3); }
    .input-group:focus-within label { color: #3b82f6; }
    .input-focus:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-md">
    <div class="text-center mb-10">
      <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200 mx-auto mb-4">
        <i class="fas fa-fingerprint text-white text-3xl"></i>
      </div>
      <h1 class="text-2xl font-black text-slate-800 tracking-tight">Selamat Datang Kembali</h1>
      <p class="text-slate-500 font-medium">Silakan login untuk mengakses dashboard</p>
    </div>

    <div class="auth-card p-8 md:p-10">
      @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-sm font-bold">
          {{ $errors->first() }}
        </div>
      @endif

      @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm font-bold">
          {{ session('success') }}
        </div>
      @endif

      <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="space-y-6">
          <div class="input-group">
            <label for="username" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Username</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                <i class="fas fa-user"></i>
              </span>
              <input type="text" id="username" name="username" class="input-focus w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 transition-all" placeholder="Masukkan username" required autofocus>
            </div>
          </div>

          <div class="input-group">
            <div class="flex justify-between items-center mb-2 ml-1">
              <label for="password" class="block text-xs font-black text-slate-400 uppercase tracking-widest">Password</label>
              <a href="#" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-700">Lupa Password?</a>
            </div>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                <i class="fas fa-lock"></i>
              </span>
              <input type="password" id="password" name="password" class="input-focus w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 transition-all" placeholder="Masukkan password" required>
            </div>
          </div>

          <button type="submit" class="w-full btn-gradient py-4 rounded-2xl text-white font-black text-sm uppercase tracking-widest shadow-lg shadow-blue-200">
            Masuk Sekarang <i class="fas fa-arrow-right ml-2"></i>
          </button>
        </div>
      </form>

      <div class="mt-10 pt-8 border-t border-slate-50 text-center">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum punya akun?</p>
        <a href="{{ route('register') }}" class="mt-2 inline-block text-sm font-black text-blue-600 hover:text-blue-700 transition-colors">
          Buat Akun Baru
        </a>
      </div>
    </div>
    
    <div class="mt-10 text-center">
      <a href="{{ route('absensi.view') }}" class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] hover:text-blue-500 transition-colors">
        <i class="fas fa-qrcode mr-2"></i> Buka Terminal Absensi
      </a>
    </div>
  </div>
</body>
</html>
