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
    .password-strength { height: 4px; border-radius: 2px; transition: all 0.3s ease; }
    .strength-weak { background-color: #ef4444; width: 25%; }
    .strength-medium { background-color: #f59e0b; width: 50%; }
    .strength-strong { background-color: #10b981; width: 100%; }
    video { border-radius: 10px; }
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
        <a href="absensi.html" class="text-blue-100 hover:text-white transition-colors flex items-center">
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
          <span class="ml-2 text-sm font-medium text-gray-500">Verifikasi</span>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="p-6">
      <form id="registrationForm">
        <div id="step2" class="space-y-6">
          <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" class="input-field w-full px-4 py-2 rounded-lg" placeholder="Masukkan username" required>
              </div>
              <div>
                <label for="employeeId" class="block text-sm font-medium text-gray-700 mb-1">ID Karyawan</label>
                <input type="text" id="employeeId" class="input-field w-full px-4 py-2 rounded-lg" placeholder="EMP-XXXX" required>
              </div>
            </div>

            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" class="input-field w-full px-4 py-2 rounded-lg mb-4" placeholder="nama@perusahaan.com" required>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
  <div>
    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
    <input type="text" id="name" name="name" class="input-field w-full px-4 py-2 rounded-lg" placeholder="Masukkan nama lengkap" required>
  </div>
  <div>
    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Peran</label>
    <select id="role" name="role" class="input-field w-full px-4 py-2 rounded-lg" required>
      <option value="">Pilih peran</option>
      <option value="siswa">Siswa</option>
      <option value="guru">Guru</option>
    </select>
  </div>
</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" class="input-field w-full px-4 py-2 rounded-lg" required>
                <div id="passwordStrength" class="password-strength"></div>
              </div>
              <div>
                <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" id="confirmPassword" class="input-field w-full px-4 py-2 rounded-lg" required>
              </div>
            </div>
          </div>

          <!-- Register Face -->
          <div class="border-t border-gray-200 pt-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Pendaftaran Wajah</h2>
            <p class="text-sm text-gray-600 mb-4">Pastikan wajah terlihat jelas di kamera.</p>

            <div class="flex flex-col items-center space-y-4">
              <video id="video" width="320" height="240" autoplay muted class="border"></video>
              <canvas id="canvas" width="320" height="240" class="hidden"></canvas>
              <button type="button" id="captureFace" class="btn-primary text-white px-6 py-3 rounded-lg font-medium flex items-center">
                <i class="fas fa-camera mr-2"></i> Ambil Foto Wajah
              </button>
            </div>
          </div>
        </div>

        <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
          <a href="register-step1.html" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium flex items-center hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
          </a>
          <button type="submit" id="submitBtn" class="btn-primary text-white px-6 py-2 rounded-lg font-medium flex items-center">
            Daftar Akun <i class="fas fa-arrow-right ml-2"></i>
          </button>
        </div>
      </form>
    </div>

    <div class="bg-gray-100 border-t border-gray-200 p-4 text-center text-sm text-gray-600">
      <p>Sistem Absensi Digital &copy; 2025 - Divisi Teknologi Informasi</p>
    </div>
  </div>

  <script>
    const user_id = "{{ Auth::check() ? Auth::id() : '' }}";
    const username = "{{ Auth::check() ? Auth::user()->username : '' }}";
</script>


 <script>
  // server-side values (safe)
  const SERVER_USER_ID = {!! json_encode(Auth::check() ? Auth::id() : null) !!};
  const SERVER_USERNAME = {!! json_encode(Auth::check() ? Auth::user()->username : null) !!};
  const CSRF_TOKEN = {!! json_encode(csrf_token()) !!};

  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const captureBtn = document.getElementById('captureFace');
  const registrationForm = document.getElementById('registrationForm');
  const submitBtn = document.getElementById('submitBtn');

  // init webcam
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => { video.srcObject = stream; })
    .catch(err => { alert("Gagal mengakses kamera: " + err); });

  // helpers
  function captureImageData() {
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    return canvas.toDataURL('image/jpeg', 0.9);
  }

  function savePendingFaceToLocal(imageData, localUsername = '') {
    try {
      localStorage.setItem('absensi_pending_face', imageData);
      if (localUsername) localStorage.setItem('absensi_pending_username', localUsername);
      return true;
    } catch (e) {
      console.warn('localStorage save failed', e);
      return false;
    }
  }

  function getPendingFaceFromLocal() {
    return localStorage.getItem('absensi_pending_face') || null;
  }

  function clearPendingFaceFromLocal() {
    localStorage.removeItem('absensi_pending_face');
    localStorage.removeItem('absensi_pending_username');
  }

  async function postJson(url, bodyObj) {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify(bodyObj),
    });
    const json = await res.json().catch(()=>null);
    return { ok: res.ok, status: res.status, json };
  }

  // Single handler for capture button
  captureBtn.addEventListener('click', async () => {
    captureBtn.disabled = true;
    const originalHTML = captureBtn.innerHTML;
    captureBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengambil Wajah...';

    const imageData = captureImageData();
    // if server-side user exists -> send directly (registered user flow)
    if (SERVER_USER_ID) {
      try {
        // send to Flask or Laravel forwarding endpoint (sesuaikan url jika perlu)
        const { ok, json } = await postJson('http://127.0.0.1:5000/api/register-face', {
          username: SERVER_USERNAME,
          user_id: SERVER_USER_ID,
          image: imageData
        });

        if (ok && json && (json.message || json.success)) {
          captureBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Wajah Terdaftar';
          captureBtn.classList.remove('btn-primary');
          captureBtn.classList.add('bg-green-600');
        } else {
          const msg = (json && (json.error || json.message)) ? (json.error || json.message) : 'Gagal mendaftar wajah';
          alert('Gagal mendaftar wajah: ' + msg);
          captureBtn.innerHTML = originalHTML;
        }
      } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan saat mendaftar wajah: ' + err.message);
        captureBtn.innerHTML = originalHTML;
      } finally {
        captureBtn.disabled = false;
      }
      return;
    }

    // user belum login -> simpan sementara dan beri instruksi untuk melanjutkan pendaftaran
    const uname = document.getElementById('username') ? document.getElementById('username').value.trim() : '';
    const saved = savePendingFaceToLocal(imageData, uname);
    if (saved) {
      alert('Foto wajah disimpan sementara. Lengkapi formulir lalu klik "Daftar Akun". Setelah akun dibuat, foto akan otomatis dikirim sebagai pendaftaran wajah.');
      captureBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Foto Disimpan';
      captureBtn.classList.remove('btn-primary');
      captureBtn.classList.add('bg-yellow-500');
    } else {
      alert('Gagal menyimpan foto sementara. Pastikan browser mengizinkan penyimpanan lokal.');
      captureBtn.innerHTML = originalHTML;
    }
    captureBtn.disabled = false;
  });

  // Submit register form (AJAX). Backend harus merespon JSON { id: <user_id> } ketika sukses.
  registrationForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    submitBtn.disabled = true;
    const oldHTML = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

    // collect form data (multipart form)
    const fd = new FormData(registrationForm);
    // append CSRF if not already present in form
    if (!fd.get('_token')) fd.append('_token', CSRF_TOKEN);

    try {
      const res = await fetch("{{ route('register.post') }}", {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      // try to parse JSON (expected)
      const respJson = await res.json().catch(()=>null);

      if (res.ok && respJson) {
        // expect backend returns user id: try common keys
        const newUserId = respJson.id || respJson.user_id || respJson.data && respJson.data.id;
        if (newUserId) {
          // if pending face exists -> send it to backend face-save endpoint
          const pending = getPendingFaceFromLocal();
          if (pending) {
            // send to Laravel endpoint which will forward/save encoding
            const faceRes = await postJson("{{ url('/face-encoding/save') }}", {
              user_id: newUserId,
              image: pending
            });

            if (faceRes.ok) {
              clearPendingFaceFromLocal();
              alert('Registrasi berhasil. Wajah juga berhasil didaftarkan. Silakan login.');
              window.location.href = "{{ route('login') }}";
              return;
            } else {
              console.warn('face save responded', faceRes);
              alert('Registrasi berhasil, tapi gagal menyimpan wajah otomatis. Silakan login lalu daftarkan wajah.');
              window.location.href = "{{ route('login') }}";
              return;
            }
          } else {
            alert('Registrasi berhasil. Silakan login.');
            window.location.href = "{{ route('login') }}";
            return;
          }
        } else {
          // backend OK but no id returned — fallback: redirect (server may do redirect)
          alert('Registrasi selesai. Silakan login.');
          window.location.href = "{{ route('login') }}";
          return;
        }
      } else {
        // handle validation errors or html response fallback
        let message = 'Gagal registrasi';
        if (respJson) {
          // Laravel validation errors often in errors object
          if (respJson.errors) {
            message = Object.values(respJson.errors).flat().join(', ');
          } else if (respJson.message) {
            message = respJson.message;
          }
        } else {
          // if server returned non-json (html), try to show status
          message = `Request failed (status ${res.status})`;
        }
        alert(message);
      }
    } catch (err) {
      console.error(err);
      alert('Terjadi kesalahan saat registrasi: ' + err.message);
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerHTML = oldHTML;
    }
  });
</script>
</body>
</html>
