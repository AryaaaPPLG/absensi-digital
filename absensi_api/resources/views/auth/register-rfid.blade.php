<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi RFID - Sistem Absensi Digital</title>
  
  <style>
    body { 
        font-family: 'Inter', sans-serif; 
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
            radial-gradient(ellipse 500px 350px at 50% 30%, rgba(59, 130, 246, 0.08), transparent),
            radial-gradient(ellipse 400px 300px at 30% 70%, rgba(139, 92, 246, 0.06), transparent);
    }
    .form-container { 
        background: rgba(15, 17, 23, 0.8); 
        border: 1px solid rgba(59, 130, 246, 0.1);
        box-shadow: 0 28px 80px rgba(0, 0, 0, 0.4), 0 0 40px rgba(59, 130, 246, 0.05);
        backdrop-filter: blur(20px);
    }
    .btn-primary { 
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); 
        transition: all 0.3s ease; 
    }
    .btn-primary:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
    }
    .input-field { 
        transition: all 0.3s ease; 
        border: 1px solid rgba(255, 255, 255, 0.1); 
        background: rgba(255, 255, 255, 0.05);
        color: #e2e8f0;
    }
    .input-field:focus { 
        border-color: rgba(59, 130, 246, 0.4); 
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); 
        outline: none;
    }
    .text-gradient {
        background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
  </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-md form-container rounded-3xl overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600/20 to-purple-600/20 border-b border-white/5 text-white p-6 text-center backdrop-blur-sm">
      <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/20">
        <i class="fas fa-id-card text-white text-2xl"></i>
      </div>
      <h1 class="text-2xl font-black text-slate-100">Registrasi Kartu RFID</h1>
      <p class="text-slate-400 mt-1 text-sm">Tempelkan kartu pada reader atau masukkan ID kartu</p>
    </div>
    
    <div class="p-6">
      <form action="{{ route('rfid.register') }}" method="POST">
        @csrf
        <div class="mb-6">
          <label for="rfid_uid" class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-wider text-[10px]">RFID UID / ID Kartu</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
              <i class="fas fa-id-card"></i>
            </span>
            <input type="text" id="rfid_uid" name="rfid_uid" class="input-field w-full pl-10 pr-4 py-3 rounded-xl focus:outline-none" placeholder="Masukkan atau scan kartu..." required autofocus>
          </div>
          <p class="text-xs text-slate-600 mt-2 italic">*Jika menggunakan reader USB, pastikan kursor berada di kotak input ini saat melakukan tapping.</p>
        </div>

        <button type="submit" class="w-full btn-primary text-white py-3 rounded-xl font-bold text-lg shadow-lg uppercase tracking-wider">
          SIMPAN KARTU <i class="fas fa-save ml-2"></i>
        </button>
      </form>

      <div class="mt-6 text-center">
        <a href="{{ route('dashboard') }}" class="text-sm text-slate-500 hover:text-blue-400 transition-colors">
          Lewati untuk sekarang <i class="fas fa-arrow-right ml-1"></i>
        </a>
      </div>
    </div>
  </div>
</body>
</html>
