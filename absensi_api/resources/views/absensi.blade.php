<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terminal RFID - Sistem Absensi Digital</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: #020617; 
        color: white;
        overflow-x: hidden;
    }
    .scan-container { 
        border: 2px dashed rgba(59, 130, 246, 0.3); 
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); 
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
    }
    .scan-active { 
        border-color: #3b82f6; 
        background: rgba(59, 130, 246, 0.1);
        box-shadow: 0 0 50px rgba(59, 130, 246, 0.2);
    }
    .pulse-blue { 
        animation: pulse-animation 2s infinite; 
    }
    @keyframes pulse-animation {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5); }
      70% { transform: scale(1); box-shadow: 0 0 0 20px rgba(59, 130, 246, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    .activity-row { animation: slide-in 0.5s ease-out; }
    @keyframes slide-in {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .text-gradient {
        background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .swal2-rfid-popup {
        border-radius: 32px !important;
        padding: 30px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        box-shadow: 0 28px 90px rgba(2, 6, 23, 0.38) !important;
    }
    .swal2-rfid-title {
        color: #0f172a !important;
        font-size: 1.45rem !important;
        font-weight: 900 !important;
        letter-spacing: -0.015em !important;
    }
    .swal2-rfid-html,
    .swal2-rfid-popup .swal2-html-container {
        color: #475569 !important;
        margin-top: 10px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .swal2-rfid-confirm {
        border-radius: 16px !important;
        padding: 13px 28px !important;
        font-size: 0.75rem !important;
        font-weight: 900 !important;
        letter-spacing: 0.16em !important;
        text-transform: uppercase !important;
        box-shadow: 0 14px 28px rgba(37, 99, 235, 0.28) !important;
    }
    .swal2-timer-progress-bar {
        background: linear-gradient(90deg, #22c55e, #2563eb) !important;
    }
  </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center antialiased">
  
  <div class="w-full max-w-7xl px-6">
    <div class="text-center mb-12 mt-12">
      <div class="inline-flex items-center px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full space-x-2 mb-6">
          <span class="flex h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
          <span class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em]">Live RFID Terminal v2.0</span>
      </div>
      <h1 class="text-5xl font-black mb-3 tracking-tight">SISTEM <span class="text-gradient">ABSENSI DIGITAL</span></h1>
      <p class="text-slate-400 text-lg font-medium">Silakan tempelkan kartu RFID Anda pada reader untuk melakukan absensi.</p>
      
      <div id="realtimeClock" class="text-2xl font-black text-white mt-8 bg-slate-900/80 backdrop-blur-md inline-block px-10 py-4 rounded-[2rem] border border-slate-800 shadow-2xl">
          <!-- JS Clock -->
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
      <!-- Left Column: Scanner Visualizer -->
      <div class="space-y-8">
        <div id="scannerArea" class="scan-container rounded-[3rem] p-16 flex flex-col items-center justify-center relative overflow-hidden group">
          <div class="bg-blue-600/10 p-10 rounded-full mb-8 pulse-blue border border-blue-500/20">
            <i class="fas fa-id-card text-8xl text-blue-500 group-hover:scale-110 transition-transform"></i>
          </div>
          <p id="instructionText" class="text-2xl font-black text-white tracking-tight uppercase tracking-[0.1em]">Menunggu Kartu...</p>
          <p class="text-slate-500 mt-2 font-bold uppercase text-xs tracking-widest">TAP DISINI</p>
          
          <!-- Hidden input for RFID reader -->
          <input type="text" id="rfidInput" class="absolute opacity-0 pointer-events-none" autofocus>
          
          <!-- Decorative element -->
          <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="bg-slate-900/50 backdrop-blur-md p-6 rounded-[2rem] border border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-green-500/20 text-green-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-shield text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Status Sistem</p>
                    <p class="text-sm font-bold text-white uppercase tracking-tight">Terminal Aktif & Online</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Device ID</p>
                <p class="text-sm font-mono font-bold text-blue-400 uppercase tracking-tight">TERM-001</p>
            </div>
        </div>
      </div>

      <!-- Right Column: Recent Activity -->
      <div class="bg-slate-900/80 backdrop-blur-xl rounded-[3rem] overflow-hidden shadow-2xl border border-slate-800">
        <div class="p-8 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
          <div>
            <h3 class="text-xl font-black text-white tracking-tight">Aktivitas Terbaru</h3>
            <p class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em] mt-1">Hari Ini</p>
          </div>
          <i class="fas fa-history text-slate-700 text-xl"></i>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full" id="activityTable">
            <thead>
              <tr class="text-left text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] bg-slate-900/80">
                <th class="py-5 px-8">Siswa</th>
                <th class="py-5 px-6">Masuk</th>
                <th class="py-5 px-6">Pulang</th>
                <th class="py-5 px-8 text-right">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800" id="activityBody">
              @forelse($recentAttendances as $att)
              <tr class="activity-row group hover:bg-white/5 transition-colors" id="row-{{ $att->user_id }}">
                <td class="py-5 px-8">
                  <div class="flex flex-col">
                    <span class="font-bold text-slate-200 group-hover:text-white transition-colors">{{ $att->user->name }}</span>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ $att->user->schoolClass?->nama_kelas ?? '-' }} / {{ $att->user->schoolClass?->jurusan ?? '-' }}</span>
                  </div>
                </td>
                <td class="py-5 px-6 text-slate-400 text-sm font-bold time-in">
                  {{ $att->time_in }}
                </td>
                <td class="py-5 px-6 text-slate-400 text-sm font-bold time-out">
                  {{ $att->time_out ?? '--:--' }}
                </td>
                <td class="py-5 px-8 text-right">
                  <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $att->status === 'hadir' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-500' }}">
                    {{ $att->status }}
                  </span>
                </td>
              </tr>
              @empty
              <tr id="emptyState">
                <td colspan="4" class="py-24 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center text-slate-600 mb-4 text-2xl">
                            <i class="fas fa-clipboard-user"></i>
                        </div>
                        <p class="text-slate-500 font-black uppercase tracking-widest text-xs italic">Belum ada aktivitas hari ini</p>
                    </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Referensi ke elemen-elemen HTML yang dibutuhkan
    const rfidInput = document.getElementById('rfidInput');
    const scannerArea = document.getElementById('scannerArea');
    const instructionText = document.getElementById('instructionText');
    const activityBody = document.getElementById('activityBody');
    const emptyState = document.getElementById('emptyState');
    const realtimeClock = document.getElementById('realtimeClock');
    const RfidAlert = Swal.mixin({
      background: '#ffffff',
      color: '#1e293b',
      confirmButtonColor: '#2563eb',
      buttonsStyling: true,
      customClass: {
        popup: 'swal2-rfid-popup',
        title: 'swal2-rfid-title',
        htmlContainer: 'swal2-rfid-html',
        confirmButton: 'swal2-rfid-confirm'
      }
    });

    // Fungsi untuk memperbarui jam digital secara real-time
    function updateClock() {
      const now = new Date();
      const options = { 
        weekday: 'short', day: 'numeric', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit',
        hour12: false, timeZone: 'Asia/Jakarta'
      };
      realtimeClock.textContent = now.toLocaleDateString('id-ID', options) + ' WIB';
    }
    
    // Jalankan jam setiap 1 detik
    setInterval(updateClock, 1000);
    updateClock();

    // TEKNIK PENTING: Paksa input RFID selalu fokus (focus)
    // Karena scanner RFID bekerja seperti keyboard yang mengetik cepat lalu menekan Enter.
    // Jika input tidak fokus, data scanner tidak akan tertangkap.
    document.addEventListener('click', () => rfidInput.focus());
    window.onload = () => rfidInput.focus();

    function escapeHtml(value) {
      const div = document.createElement('div');
      div.textContent = value ?? '';
      return div.innerHTML;
    }

    // Menangkap input dari scanner RFID
    rfidInput.addEventListener('keypress', async (e) => {
      // Scanner RFID biasanya mengirimkan tombol 'Enter' di akhir pembacaan UID
      if (e.key === 'Enter') {
        const uid = rfidInput.value.trim();
        if (uid) processScan(uid); // Kirim UID ke server untuk diproses
        rfidInput.value = ''; // Kosongkan input agar siap untuk scan berikutnya
      }
    });

    // Fungsi untuk mengirim data UID ke server via API
    async function processScan(uid) {
      // Efek visual: beri animasi pada area scanner saat memproses
      scannerArea.classList.add('scan-active');
      instructionText.textContent = 'Memproses...';
      
      try {
        // Mengirim data UID ke RfidController@scan melalui route API
        const response = await fetch('/api/attendance/scan', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ rfid_uid: uid })
        });

        const data = await response.json();

        if (response.ok) {
          // Jika Berhasil: Tampilkan notifikasi SweetAlert sukses
          showStatus('success', data);
          updateActivityRow(data); // Tambahkan baris baru ke tabel aktivitas tanpa refresh
        } else {
          // Jika Gagal (Kartu tidak terdaftar/sudah absen): Tampilkan notifikasi error
          showStatus('error', data);
        }
      } catch (error) {
        showStatus('error', { message: 'Terjadi kesalahan koneksi sistem.' });
      } finally {
        // Kembalikan status tampilan ke semula setelah 2 detik
        setTimeout(() => {
          scannerArea.classList.remove('scan-active');
          instructionText.textContent = 'Menunggu Kartu...';
        }, 2000);
      }
    }

    function showStatus(type, data) {
      if (type === 'success') {
        const isOut = data.type === 'out';
        const userName = escapeHtml(data.user);
        const kelas = escapeHtml(data.kelas || '-');
        const jurusan = escapeHtml(data.jurusan || '-');
        const scanTime = escapeHtml(data.time);
        const message = escapeHtml(data.message || 'Absensi berhasil diproses.');

        RfidAlert.fire({
          icon: 'success',
          title: isOut ? 'Absensi Pulang Tercatat' : 'Absensi Masuk Tercatat',
          html: `
            <div style="text-align:left;margin-top:18px;padding:20px;border-radius:24px;background:#f8fafc;border:1px solid #e2e8f0;">
              <div style="display:flex;justify-content:space-between;gap:18px;margin-bottom:12px;">
                <span style="color:#94a3b8;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.16em;">Nama</span>
                <span style="color:#0f172a;font-weight:900;text-align:right;">${userName}</span>
              </div>
              <div style="display:flex;justify-content:space-between;gap:18px;margin-bottom:12px;">
                <span style="color:#94a3b8;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.16em;">Kelas</span>
                <span style="color:#334155;font-weight:800;text-align:right;">${kelas} / ${jurusan}</span>
              </div>
              <div style="display:flex;justify-content:space-between;gap:18px;padding-top:12px;border-top:1px solid #e2e8f0;">
                <span style="color:#94a3b8;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.16em;">Waktu</span>
                <span style="color:#2563eb;font-weight:900;text-align:right;">${scanTime} WIB</span>
              </div>
            </div>
            <p style="margin-top:16px;color:#64748b;font-size:13px;font-weight:800;line-height:1.6;">${message}</p>
          `,
          showConfirmButton: false,
          timer: 3300,
          timerProgressBar: true
        });
      } else {
        const message = data.message || 'Scan tidak dapat diproses. Silakan coba lagi.';
        const isWarning = message.toLowerCase().includes('sudah');
        const safeMessage = escapeHtml(message);

        RfidAlert.fire({
          icon: isWarning ? 'warning' : 'error',
          title: isWarning ? 'Absensi Sudah Tercatat' : 'Scan Tidak Berhasil',
          html: `<p style="margin:0;color:#64748b;font-weight:700;line-height:1.6;">${safeMessage}</p>`,
          confirmButtonText: 'Mengerti'
        });
      }
    }

    function updateActivityRow(data) {
      if (emptyState) emptyState.remove();

      const existingRow = activityBody.querySelector(`[id="row-${data.user_id}"]`);
      
      if (data.type === 'out' && existingRow) {
        existingRow.querySelector('.time-out').textContent = data.time;
        existingRow.classList.add('bg-blue-500/10');
        setTimeout(() => existingRow.classList.remove('bg-blue-500/10'), 2000);
        return;
      }

      const row = document.createElement('tr');
      row.id = `row-${data.user_id || Date.now()}`;
      row.className = 'activity-row group border-b border-slate-800 hover:bg-white/5 transition-colors';
      row.innerHTML = `
        <td class="py-5 px-8">
          <div class="flex flex-col">
            <span class="font-bold text-slate-200 group-hover:text-white transition-colors">${data.user}</span>
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">${data.kelas || '-'} / ${data.jurusan || '-'}</span>
          </div>
        </td>
        <td class="py-5 px-6 text-slate-400 text-sm font-bold time-in">
          ${data.time}
        </td>
        <td class="py-5 px-6 text-slate-400 text-sm font-bold time-out">
          --:--
        </td>
        <td class="py-5 px-8 text-right">
          <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-500">
            HADIR
          </span>
        </td>
      `;

      activityBody.prepend(row);
      if (activityBody.children.length > 10) activityBody.removeChild(activityBody.lastChild);
    }
  </script>
</body>
</html>
