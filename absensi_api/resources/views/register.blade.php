<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Akun - Sistem Absensi Digital</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    .form-container { background-color: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); }
    .btn-primary { background-color: #3b82f6; transition: all 0.3s ease; }
    .btn-primary:hover { background-color: #2563eb; transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(59,130,246,0.3); }
    .input-field { transition: all 0.3s ease; border: 1px solid #d1d5db; }
    .input-field:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .step-indicator { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; }
    .step-active { background-color: #3b82f6; color: white; }
    .step-inactive { background-color: #e5e7eb; color: #6b7280; }
    .step-completed { background-color: #10b981; color: white; }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-2xl form-container rounded-xl overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold">Registrasi Akun Baru</h1>
          <p class="text-blue-100 mt-1">Buat akun untuk mengakses sistem absensi digital</p>
        </div>
        <a href="{{ route('login') }}" class="text-blue-100 hover:text-white transition-colors flex items-center">
          <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
      </div>
    </div>

    <!-- Progress -->
    <div class="bg-white border-b border-gray-200 p-4">
      <div class="flex justify-center items-center space-x-8">
        <div class="flex items-center">
          <div class="step-indicator step-completed"><i class="fas fa-check text-xs"></i></div>
          <span class="ml-2 text-sm font-medium text-gray-700">Data Pribadi</span>
        </div>
        <div class="flex-1 h-1 bg-gray-200"></div>
        <div class="flex items-center">
          <div class="step-indicator step-active">2</div>
          <span class="ml-2 text-sm font-medium text-gray-700">Data Akun</span>
        </div>
        <div class="flex-1 h-1 bg-gray-200"></div>
        <div class="flex items-center">
          <div class="step-indicator step-inactive">3</div>
          <span class="ml-2 text-sm font-medium text-gray-500">Registrasi RFID</span>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="p-6">
      @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="space-y-6">
          <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" class="input-field w-full px-4 py-2 rounded-lg" placeholder="Masukkan username" required>
              </div>
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="input-field w-full px-4 py-2 rounded-lg" placeholder="Masukkan nama lengkap" required>
              </div>
            </div>

            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="input-field w-full px-4 py-2 rounded-lg mb-4" placeholder="nama@perusahaan.com" required>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Peran</label>
                <select id="role" name="role" class="input-field w-full px-4 py-2 rounded-lg" required>
                  <option value="">Pilih peran</option>
                  <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                  <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
              </div>
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" class="input-field w-full px-4 py-2 rounded-lg" required>
              </div>
            </div>
            
            <div>
              <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
              <input type="password" id="confirmPassword" name="password_confirmation" class="input-field w-full px-4 py-2 rounded-lg" required>
            </div>
          </div>
        </div>

        <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
          <a href="/" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium flex items-center hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Batal
          </a>
          <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium flex items-center shadow-lg">
            Daftar & Lanjut ke RFID <i class="fas fa-arrow-right ml-2"></i>
          </button>
        </div>
      </form>
    </div>

    <div class="bg-gray-100 border-t border-gray-200 p-4 text-center text-sm text-gray-600">
      <p>Sistem Absensi Digital &copy; 2026 - Divisi Teknologi Informasi</p>
    </div>
  </div>
</body>
</html>
