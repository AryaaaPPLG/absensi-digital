<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scan Absensi RFID - Sistem Absensi Digital</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        <!-- Status Result -->
        <div id="statusCard" class="status-card bg-slate-800 rounded-2xl p-6 border-l-8 shadow-2xl">
          <div class="flex items-center">
            <div id="statusIcon" class="mr-5 text-4xl"></div>
            <div>
              <h2 id="statusTitle" class="text-2xl font-bold"></h2>
              <p id="statusMessage" class="text-slate-400 mt-1"></p>
              <div id="userDetails" class="mt-3 flex flex-wrap items-center text-sm gap-2">
                <span class="bg-slate-700 px-3 py-1 rounded-full text-blue-400 font-semibold" id="userName"></span>
                <span class="bg-blue-600/20 px-3 py-1 rounded-full text-blue-300 font-bold" id="userKelasJurusan"></span>
                <span class="text-slate-500" id="scanTime"></span>
              </div>
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
                <th class="py-4 px-6">Kelas/Jurusan</th>
                <th class="py-4 px-6">Waktu</th>
                <th class="py-4 px-6 text-right">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700" id="activityBody">
              @forelse($recentAttendances as $att)
              <tr class="activity-row">
                <td class="py-4 px-6">
                  <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-600/20 flex items-center justify-center text-blue-400 font-bold text-xs mr-3">
                      {{ substr($att->user->name, 0, 1) }}
                    </div>
                    <span class="font-semibold text-slate-200">{{ $att->user->name }}</span>
                  </div>
                </td>
                <td class="py-4 px-6 text-slate-300 text-sm font-medium">
                  {{ $att->user->kelas }} / {{ $att->user->jurusan }}
                </td>
                <td class="py-4 px-6 text-slate-400 text-sm">
                  {{ $att->time_in }}
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
    const statusCard = document.getElementById('statusCard');
    const statusTitle = document.getElementById('statusTitle');
    const statusMessage = document.getElementById('statusMessage');
    const statusIcon = document.getElementById('statusIcon');
    const userName = document.getElementById('userName');
    const userKelasJurusan = document.getElementById('userKelasJurusan');
    const scanTime = document.getElementById('scanTime');
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
          addActivityRow(data);
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
      statusCard.classList.remove('show', 'border-green-500', 'border-red-500', 'border-yellow-500');
      
      if (type === 'success') {
        statusCard.classList.add('show', 'border-green-500');
        statusIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
        statusTitle.textContent = 'Berhasil Hadir';
        statusTitle.className = 'text-2xl font-bold text-green-400';
        statusMessage.textContent = data.message;
        userName.textContent = data.user;
        userKelasJurusan.textContent = (data.kelas || '-') + ' / ' + (data.jurusan || '-');
        scanTime.textContent = data.time;
        document.getElementById('userDetails').style.display = 'flex';
      } else {
        const isWarning = data.message.includes('sudah');
        statusCard.classList.add('show', isWarning ? 'border-yellow-500' : 'border-red-500');
        statusIcon.innerHTML = isWarning 
          ? '<i class="fas fa-exclamation-triangle text-yellow-500"></i>' 
          : '<i class="fas fa-times-circle text-red-500"></i>';
        statusTitle.textContent = isWarning ? 'Sudah Absen' : 'Gagal Absen';
        statusTitle.className = 'text-2xl font-bold ' + (isWarning ? 'text-yellow-400' : 'text-red-400');
        statusMessage.textContent = data.message;
        
        if (data.user) {
          userName.textContent = data.user;
          userKelasJurusan.textContent = (data.kelas || '-') + ' / ' + (data.jurusan || '-');
          scanTime.textContent = data.time || '';
          document.getElementById('userDetails').style.display = 'flex';
        } else {
          document.getElementById('userDetails').style.display = 'none';
        }
      }

      // Hide status card after 5 seconds
      setTimeout(() => {
        statusCard.classList.remove('show');
      }, 5000);
    }

    function addActivityRow(data) {
      if (emptyState) {
        emptyState.remove();
      }

      const row = document.createElement('tr');
      row.className = 'activity-row border-b border-slate-700';
      row.innerHTML = `
        <td class="py-4 px-6">
          <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-blue-600/20 flex items-center justify-center text-blue-400 font-bold text-xs mr-3">
              ${data.user.charAt(0)}
            </div>
            <span class="font-semibold text-slate-200">${data.user}</span>
          </div>
        </td>
        <td class="py-4 px-6 text-slate-300 text-sm font-medium">
          ${data.kelas || '-'} / ${data.jurusan || '-'}
        </td>
        <td class="py-4 px-6 text-slate-400 text-sm">
          ${data.time}
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
