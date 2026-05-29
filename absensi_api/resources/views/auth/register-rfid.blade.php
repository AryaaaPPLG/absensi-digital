<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi RFID - Sistem Absensi Digital</title>
  
  
  
  <style>
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    .form-container { background-color: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); }
    .btn-primary { background-color: #3b82f6; transition: all 0.3s ease; }
    .btn-primary:hover { background-color: #2563eb; transform: translateY(-2px); }
    .input-field { transition: all 0.3s ease; border: 1px solid #d1d5db; }
    .input-field:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
  </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-md form-container rounded-xl overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 text-center">
      <h1 class="text-2xl font-bold">Registrasi Kartu RFID</h1>
      <p class="text-blue-100 mt-1">Tempelkan kartu pada reader atau masukkan ID kartu</p>
    </div>
    
    <div class="p-6">
      <form action="{{ route('rfid.register') }}" method="POST">
        @csrf
        <div class="mb-6">
          <label for="rfid_uid" class="block text-sm font-medium text-gray-700 mb-2">RFID UID / ID Kartu</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
              <i class="fas fa-id-card"></i>
            </span>
            <input type="text" id="rfid_uid" name="rfid_uid" class="input-field w-full pl-10 pr-4 py-3 rounded-lg focus:outline-none" placeholder="Masukkan atau scan kartu..." required autofocus>
          </div>
          <p class="text-xs text-gray-500 mt-2 italic">*Jika menggunakan reader USB, pastikan kursor berada di kotak input ini saat melakukan tapping.</p>
        </div>

        <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-bold text-lg shadow-lg">
          SIMPAN KARTU <i class="fas fa-save ml-2"></i>
        </button>
      </form>

      <div class="mt-6 text-center">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-blue-600">
          Lewati untuk sekarang <i class="fas fa-arrow-right ml-1"></i>
        </a>
      </div>
    </div>
  </div>
</body>
</html>
