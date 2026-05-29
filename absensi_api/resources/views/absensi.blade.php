<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terminal RFID - Sistem Absensi Digital</title>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    body { font-family: 'Inter', sans-serif; background-color: #0f172a; color: white; }
    .scan-container { border: 2px dashed #334155; transition: all 0.3s ease; }
    .scan-active { border-color: #3b82f6; background-color: rgba(59, 130, 246, 0.1); }
    .status-card { display: none; transform: translateY(20px); transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .status-card.show { display: block; transform: translateY(0); }
    .pulse { animation: pulse-animation 2s infinite; }
    @keyframes pulse-animation {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
      70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    .activity-row { animation: slide-in 0.5s ease-out; }
    @keyframes slide-in {
      from { opacity: 0; transform: translateX(-20px); }
      to { opacity: 1; transform: translateX(0); }
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="min-h-screen p-4 flex flex-col items-center">
  
  <div class="w-full max-w-6xl">
    <div class="text-center mb-10 mt-8">
      <h1 class="text-4xl font-black mb-2 tracking-tight">SISTEM ABSENSI <span class="text-blue-500">RFID</span></h1>
      <p class="text-slate-400 text-lg">Silakan tempelkan kartu RFID Anda pada reader</p>
      <div id="realtimeClock" class="text-2xl font-mono font-bold text-blue-400 mt-4 bg-slate-800/50 inline-block px-6 py-2 rounded-2xl border border-slate-700"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
      <!-- Left Column: Scanner -->
      <div class="space-y-6">
        <!-- Scanner Visualizer -->
        <div id="scannerArea" class="scan-container rounded-3xl p-12 flex flex-col items-center justify-center relative overflow-hidden bg-slate-800/50">
          <div class="bg-blue-600/20 p-8 rounded-full mb-6 pulse">
            <i class="fas fa-id-card text-7xl text-blue-500"></i>
          </div>
          <p id="instructionText" class="text-xl font-medium text-slate-300">Menunggu kartu...</p>
          
          <!-- Hidden input for RFID reader (acts like a keyboard) -->
          <input type="text" id="rfidInput" class="absolute opacity-0 pointer-events-none" autofocus>
        </div>

        <!-- Status Result (Legacy, now handled by SweetAlert2 but kept for structure) -->
        <div id="statusCard" class="status-card bg-slate-800 rounded-2xl p-6 border-l-8 shadow-2xl">
          <div class="flex items-center">
            <div id="statusIcon" class="mr-5 text-4xl"></div>
            <div>
              <h2 id="statusTitle" class="text-2xl font-bold"></h2>
              <p id="statusMessage" class="text-slate-400 mt-1"></p>
            </div>
          </div>
        </div>

        <div class="text-slate-500 flex items-center justify-center pt-4">
          <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></div>
          Sistem Aktif - Siap Menerima Scan
        </div>
      </div>

      <!-- Right Column: Activity Table -->
      <div class="bg-slate-800 rounded-3xl overflow-hidden shadow-xl border border-slate-700">
        <div class="p-6 border-b border-slate-700 flex justify-between items-center">
          <h3 class="text-xl font-bold">Aktivitas Terbaru</h3>
          <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Hari Ini</span>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full" id="activityTable">
            <thead>
              <tr class="text-left text-slate-500 text-xs font-bold uppercase tracking-widest bg-slate-900/50">
                <th class="py-4 px-6">Nama</th>
                <th class="py-4 px-6">Masuk</th>
                <th class="py-4 px-6">Pulang</th>
                <th class="py-4 px-6 text-right">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700" id="activityBody">
              @forelse($recentAttendances as $att)
              <tr class="activity-row" id="row-{{ $att->user_id }}">
                <td class="py-4 px-6">
                  <div class="flex flex-col">
                    <span class="font-semibold text-slate-200">{{ $att->user->name }}</span>
                    <span class="text-[10px] text-slate-500">{{ $att->user->schoolClass?->nama_kelas ?? '-' }} / {{ $att->user->schoolClass?->jurusan ?? '-' }}</span>
                  </div>
                </td>
                <td class="py-4 px-6 text-slate-400 text-sm font-medium time-in">
                  {{ $att->time_in }}
                </td>
                <td class="py-4 px-6 text-slate-400 text-sm font-medium time-out">
                  {{ $att->time_out ?? '-' }}
                </td>
                <td class="py-4 px-6 text-right">
                  <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ $att->status === 'hadir' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                    {{ $att->status }}
                  </span>
                </td>
              </tr>
              @empty
              <tr id="emptyState">
                <td colspan="4" class="py-10 text-center text-slate-500 italic">Belum ada aktivitas hari ini</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    const rfidInput = document.getElementById('rfidInput');
    const scannerArea = document.getElementById('scannerArea');
    const instructionText = document.getElementById('instructionText');
    const activityBody = document.getElementById('activityBody');
    const emptyState = document.getElementById('emptyState');
    const realtimeClock = document.getElementById('realtimeClock');

    function updateClock() {
      const now = new Date();
      const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit',
        timeZone: 'Asia/Jakarta'
      };
      realtimeClock.textContent = now.toLocaleDateString('id-ID', options);
    }
    
    setInterval(updateClock, 1000);
    updateClock();

    // Keep focus on hidden input
    document.addEventListener('click', () => rfidInput.focus());
    
    // Auto focus on load
    window.onload = () => rfidInput.focus();

    rfidInput.addEventListener('keypress', async (e) => {
      if (e.key === 'Enter') {
        const uid = rfidInput.value.trim();
        if (uid) {
          processScan(uid);
        }
        rfidInput.value = '';
      }
    });

    async function processScan(uid) {
      // Visual feedback
      scannerArea.classList.add('scan-active');
      instructionText.textContent = 'Memproses...';
      
      try {
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
          showStatus('success', data);
          updateActivityRow(data);
        } else {
          showStatus('error', data);
        }
      } catch (error) {
        showStatus('error', { message: 'Terjadi kesalahan koneksi sistem.' });
      } finally {
        setTimeout(() => {
          scannerArea.classList.remove('scan-active');
          instructionText.textContent = 'Menunggu kartu...';
        }, 2000);
      }
    }

    function showStatus(type, data) {
      if (type === 'success') {
        const isOut = data.type === 'out';
        Swal.fire({
          icon: 'success',
          title: isOut ? 'Berhasil Pulang' : 'Berhasil Hadir',
          html: `
            <div class="text-left mt-2 p-4 bg-slate-50 rounded-xl border border-slate-100">
              <div class="flex justify-between mb-1">
                <span class="text-slate-400 text-xs font-bold uppercase">Nama</span>
                <span class="text-slate-700 font-bold">${data.user}</span>
              </div>
              <div class="flex justify-between mb-1">
                <span class="text-slate-400 text-xs font-bold uppercase">Kelas</span>
                <span class="text-slate-700 font-bold">${data.kelas || '-'} / ${data.jurusan || '-'}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400 text-xs font-bold uppercase">Waktu</span>
                <span class="text-blue-600 font-bold">${data.time}</span>
              </div>
            </div>
            <p class="mt-4 text-slate-500 font-medium">${data.message}</p>
          `,
          showConfirmButton: false,
          timer: 4000,
          timerProgressBar: true,
          background: '#ffffff',
          color: '#1e293b',
          customClass: {
            popup: 'rounded-[2rem] border-none shadow-2xl'
          }
        });
      } else {
        const isWarning = data.message.includes('sudah');
        Swal.fire({
          icon: isWarning ? 'warning' : 'error',
          title: isWarning ? 'Sudah Absen' : 'Gagal Absen',
          text: data.message,
          confirmButtonText: 'OK',
          confirmButtonColor: '#3b82f6',
          background: '#ffffff',
          color: '#1e293b',
          customClass: {
            popup: 'rounded-[2rem] border-none shadow-2xl',
            confirmButton: 'rounded-xl px-8 py-3 font-bold uppercase tracking-wider'
          }
        });
      }
    }

    function updateActivityRow(data) {
      if (emptyState) {
        emptyState.remove();
      }

      // Try to find existing row for this user (for clock out)
      const existingRow = activityBody.querySelector(`[id="row-${data.user_id}"]`);
      
      if (data.type === 'out' && existingRow) {
        existingRow.querySelector('.time-out').textContent = data.time;
        existingRow.classList.add('bg-blue-500/5');
        setTimeout(() => existingRow.classList.remove('bg-blue-500/5'), 2000);
        return;
      }

      const row = document.createElement('tr');
      row.id = `row-${data.user_id || Date.now()}`;
      row.className = 'activity-row border-b border-slate-700';
      row.innerHTML = `
        <td class="py-4 px-6">
          <div class="flex flex-col">
            <span class="font-semibold text-slate-200">${data.user}</span>
            <span class="text-[10px] text-slate-500">${data.kelas || '-'} / ${data.jurusan || '-'}</span>
          </div>
        </td>
        <td class="py-4 px-6 text-slate-400 text-sm font-medium time-in">
          ${data.time}
        </td>
        <td class="py-4 px-6 text-slate-400 text-sm font-medium time-out">
          -
        </td>
        <td class="py-4 px-6 text-right">
          <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase bg-green-500/10 text-green-500">
            HADIR
          </span>
        </td>
      `;

      activityBody.prepend(row);

      // Keep only last 10 rows
      if (activityBody.children.length > 10) {
        activityBody.removeChild(activityBody.lastChild);
      }
    }
  </script>
</body>
</html>
