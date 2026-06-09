<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terminal RFID - Sistem Absensi Digital</title>
  
  <style>
    body { 
        background:
          radial-gradient(circle at 18% 12%, rgba(59, 130, 246, 0.15), transparent 26rem),
          radial-gradient(circle at 86% 28%, rgba(139, 92, 246, 0.1), transparent 22rem),
          radial-gradient(circle at 50% 80%, rgba(16, 185, 129, 0.05), transparent 20rem),
          linear-gradient(135deg, #050508 0%, #0a0a0f 55%, #080810 100%); 
        color: white;
        overflow-x: hidden;
    }
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: -2;
        background-image:
            radial-gradient(circle at 1px 1px, rgba(59, 130, 246, 0.04) 1px, transparent 0);
        background-size: 40px 40px;
        pointer-events: none;
    }
    .scan-container { 
        border: 2px dashed rgba(59, 130, 246, 0.2); 
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); 
        background: rgba(10, 10, 15, 0.7);
        backdrop-filter: blur(20px);
    }
    .scan-active { 
        border-color: #3b82f6; 
        background: rgba(59, 130, 246, 0.08);
        box-shadow: 0 0 60px rgba(59, 130, 246, 0.15);
    }
    .pulse-blue { 
        animation: pulse-animation 2s infinite; 
    }
    @keyframes pulse-animation {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
      70% { transform: scale(1); box-shadow: 0 0 0 20px rgba(59, 130, 246, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    .activity-row { animation: slide-in 0.5s ease-out; }
    @keyframes slide-in {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .text-gradient {
        background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 50%, #34d399 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .swal2-rfid-popup {
        border-radius: 32px !important;
        padding: 30px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        background: rgba(15, 17, 23, 0.95) !important;
        border: 1px solid rgba(59, 130, 246, 0.15) !important;
        box-shadow: 0 28px 90px rgba(0, 0, 0, 0.5), 0 0 40px rgba(59, 130, 246, 0.1) !important;
        backdrop-filter: blur(20px) !important;
    }
    .swal2-rfid-title {
        color: #f1f5f9 !important;
        font-size: 1.45rem !important;
        font-weight: 900 !important;
        letter-spacing: -0.015em !important;
    }
    .swal2-rfid-html,
    .swal2-rfid-popup .swal2-html-container {
        color: #94a3b8 !important;
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
        background: linear-gradient(135deg, #3b82f6, #8b5cf6) !important;
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.3) !important;
    }
    .swal2-timer-progress-bar {
        background: linear-gradient(90deg, #10b981, #3b82f6, #8b5cf6) !important;
    }
    .terminal-shell {
        animation: terminal-rise 600ms ease-out both;
    }
    .scanner-sweep {
        position: absolute;
        inset: 14%;
        border-radius: 2rem;
        pointer-events: none;
        overflow: hidden;
    }
    .scanner-sweep::before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: -18%;
        height: 18%;
        background: linear-gradient(180deg, transparent, rgba(96, 165, 250, 0.2), transparent);
        animation: scanner-sweep 2.8s ease-in-out infinite;
    }
    @keyframes scanner-sweep {
        0% { transform: translateY(0); opacity: 0; }
        15% { opacity: 1; }
        85% { opacity: 1; }
        100% { transform: translateY(680%); opacity: 0; }
    }
    @keyframes terminal-rise {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center antialiased">
  
  <div class="w-full max-w-7xl px-6 terminal-shell">
    <div class="text-center mb-12 mt-12">
      <div class="inline-flex items-center px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full space-x-2 mb-6">
          <span class="flex h-2 w-2 rounded-full bg-blue-400 animate-pulse"></span>
          <span class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em]">Live RFID Terminal v2.0</span>
      </div>
      <h1 class="text-5xl font-black mb-3 tracking-tight text-slate-100">SISTEM <span class="text-gradient">ABSENSI DIGITAL</span></h1>
      <p class="text-slate-500 text-lg font-medium">Silakan tempelkan kartu RFID Anda pada reader untuk melakukan absensi.</p>
      
      <div id="realtimeClock" class="text-2xl font-black text-white mt-8 bg-white/5 backdrop-blur-md inline-block px-10 py-4 rounded-[2rem] border border-white/10 shadow-2xl">
          <!-- JS Clock -->
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
      <!-- Left Column: Scanner Visualizer -->
      <div class="space-y-8">
        <div id="scannerArea" class="scan-container rounded-[3rem] p-16 flex flex-col items-center justify-center relative overflow-hidden group min-h-[30rem]">
          <div class="absolute inset-0 scan-lines opacity-35"></div>
          <div class="scanner-sweep"></div>
          <div class="orbital-ring"></div>
          <div class="bg-blue-500/10 p-10 rounded-full mb-8 pulse-blue border border-blue-500/20">
            <i class="fas fa-id-card text-8xl text-blue-400 group-hover:scale-110 transition-transform"></i>
          </div>
          <p id="instructionText" class="relative z-10 text-2xl font-black text-slate-100 tracking-tight uppercase tracking-[0.1em]">Menunggu Kartu...</p>
          <p class="text-slate-600 mt-2 font-bold uppercase text-xs tracking-widest">TAP DISINI</p>
          
          <!-- Hidden input for RFID reader -->
          <input type="text" id="rfidInput" class="absolute opacity-0 pointer-events-none" autofocus>
          
          <!-- Decorative element -->
          <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="bg-white/5 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/20">
                    <i class="fas fa-check-shield text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Status Sistem</p>
                    <p class="text-sm font-bold text-slate-200 uppercase tracking-tight">Terminal Aktif & Online</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Device ID</p>
                <p class="text-sm font-mono font-bold text-blue-400 uppercase tracking-tight">TERM-001</p>
            </div>
        </div>
      </div>

      <!-- Right Column: Recent Activity -->
      <div class="bg-white/[0.03] backdrop-blur-xl rounded-[3rem] overflow-hidden shadow-2xl border border-white/10">
        <div class="p-8 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
          <div>
            <h3 class="text-xl font-black text-slate-100 tracking-tight">Aktivitas Terbaru</h3>
            <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mt-1">Hari Ini</p>
          </div>
          <i class="fas fa-history text-slate-600 text-xl"></i>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full" id="activityTable">
            <thead>
              <tr class="text-left text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] bg-white/[0.02]">
                <th class="py-5 px-8">Siswa</th>
                <th class="py-5 px-6">Masuk</th>
                <th class="py-5 px-6">Pulang</th>
                <th class="py-5 px-8 text-right">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-white/5" id="activityBody">
              @forelse($recentAttendances as $att)
              <tr class="activity-row group hover:bg-white/[0.03] transition-colors" id="row-{{ $att->user_id }}">
                <td class="py-5 px-8">
                  <div class="flex flex-col">
                    <span class="font-bold text-slate-300 group-hover:text-slate-100 transition-colors">{{ $att->user->name }}</span>
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest mt-1">{{ $att->user->schoolClass?->nama_kelas ?? '-' }} / {{ $att->user->schoolClass?->jurusan ?? '-' }}</span>
                  </div>
                </td>
                <td class="py-5 px-6 text-slate-400 text-sm font-bold time-in">
                  {{ $att->time_in }}
                </td>
                <td class="py-5 px-6 text-slate-400 text-sm font-bold time-out">
                  {{ $att->time_out ?? '--:--' }}
                </td>
                <td class="py-5 px-8 text-right">
                  <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $att->status === 'hadir' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                    {{ $att->status }}
                  </span>
                </td>
              </tr>
              @empty
              <tr id="emptyState">
                <td colspan="4" class="py-24 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center text-slate-600 mb-4 text-2xl border border-white/5">
                            <i class="fas fa-clipboard-user"></i>
                        </div>
                        <p class="text-slate-600 font-black uppercase tracking-widest text-xs italic">Belum ada aktivitas hari ini</p>
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
    const rfidInput = document.getElementById('rfidInput');
    const scannerArea = document.getElementById('scannerArea');
    const instructionText = document.getElementById('instructionText');
    const activityBody = document.getElementById('activityBody');
    const emptyState = document.getElementById('emptyState');
    const realtimeClock = document.getElementById('realtimeClock');

    function fireTerminalAlert(options = {}) {
      const existingAlert = document.querySelector('.app-alert-backdrop');
      if (existingAlert) existingAlert.remove();

      const type = options.icon || 'info';
      const iconClass = {
        success: 'fa-circle-check',
        error: 'fa-circle-xmark',
        warning: 'fa-triangle-exclamation',
        info: 'fa-circle-info'
      }[type] || 'fa-circle-info';
      const paletteClass = {
        success: 'app-alert-success',
        error: 'app-alert-error',
        warning: 'app-alert-warning',
        info: 'app-alert-info'
      }[type] || 'app-alert-info';
      const title = options.title ? escapeHtml(options.title) : '';
      const body = options.html || (options.text ? `<p>${escapeHtml(options.text)}</p>` : '');
      const showConfirm = options.showConfirmButton !== false;
      const timer = Number(options.timer || 0);

      const backdrop = document.createElement('div');
      backdrop.className = `app-alert-backdrop ${paletteClass}`;
      backdrop.innerHTML = `
        <section class="app-alert" role="dialog" aria-modal="true" aria-live="polite">
          <div class="app-alert-icon"><i class="fas ${iconClass}"></i></div>
          ${title ? `<h2>${title}</h2>` : ''}
          <div class="app-alert-body">${body}</div>
          ${showConfirm ? `<button type="button" class="app-alert-confirm">${escapeHtml(options.confirmButtonText || 'OK')}</button>` : ''}
          ${timer && options.timerProgressBar ? '<div class="app-alert-progress"></div>' : ''}
        </section>
      `;
      document.body.appendChild(backdrop);

      const progress = backdrop.querySelector('.app-alert-progress');
      const close = () => {
        backdrop.classList.add('is-leaving');
        setTimeout(() => {
          backdrop.remove();
          rfidInput.focus();
        }, 180);
      };

      requestAnimationFrame(() => {
        backdrop.classList.add('is-visible');
        if (progress && timer) progress.style.animationDuration = `${timer}ms`;
      });

      backdrop.querySelector('.app-alert-confirm')?.addEventListener('click', close);
      if (timer) setTimeout(close, timer);

      return Promise.resolve({ isConfirmed: true });
    }

    const RfidAlert = {
      fire(options) {
        const alertApi = window.Swal || window.AppAlert;

        try {
          if (alertApi?.fire) {
            return alertApi.fire({
              background: '#0f1117',
              color: '#e2e8f0',
              confirmButtonColor: '#3b82f6',
              buttonsStyling: true,
              customClass: {
                popup: 'swal2-rfid-popup',
                title: 'swal2-rfid-title',
                htmlContainer: 'swal2-rfid-html',
                confirmButton: 'swal2-rfid-confirm'
              },
              ...options
            });
          }
        } catch (error) {
          console.error('RFID alert failed:', error);
        }

        return fireTerminalAlert(options);
      }
    };

    function updateClock() {
      const now = new Date();
      const options = { 
        weekday: 'short', day: 'numeric', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit',
        hour12: false, timeZone: 'Asia/Jakarta'
      };
      realtimeClock.textContent = now.toLocaleDateString('id-ID', options) + ' WIB';
    }
    
    setInterval(updateClock, 1000);
    updateClock();

    document.addEventListener('click', () => rfidInput.focus());
    window.onload = () => rfidInput.focus();

    function escapeHtml(value) {
      const div = document.createElement('div');
      div.textContent = value ?? '';
      return div.innerHTML;
    }

    rfidInput.addEventListener('keypress', async (e) => {
      if (e.key === 'Enter') {
        const uid = rfidInput.value.trim();
        if (uid) processScan(uid);
        rfidInput.value = '';
      }
    });

    async function processScan(uid) {
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
          updateActivityRow(data);
          showStatus('success', data);
        } else {
          showStatus('error', data);
        }
      } catch (error) {
        showStatus('error', { message: 'Terjadi kesalahan koneksi sistem.' });
      } finally {
        setTimeout(() => {
          scannerArea.classList.remove('scan-active');
          instructionText.textContent = 'Menunggu Kartu...';
          rfidInput.focus();
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
            <div style="text-align:left;margin-top:18px;padding:20px;border-radius:24px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
              <div style="display:flex;justify-content:space-between;gap:18px;margin-bottom:12px;">
                <span style="color:#64748b;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.16em;">Nama</span>
                <span style="color:#f1f5f9;font-weight:900;text-align:right;">${userName}</span>
              </div>
              <div style="display:flex;justify-content:space-between;gap:18px;margin-bottom:12px;">
                <span style="color:#64748b;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.16em;">Kelas</span>
                <span style="color:#cbd5e1;font-weight:800;text-align:right;">${kelas} / ${jurusan}</span>
              </div>
              <div style="display:flex;justify-content:space-between;gap:18px;padding-top:12px;border-top:1px solid rgba(255,255,255,0.06);">
                <span style="color:#64748b;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.16em;">Waktu</span>
                <span style="color:#60a5fa;font-weight:900;text-align:right;">${scanTime} WIB</span>
              </div>
            </div>
            <p style="margin-top:16px;color:#94a3b8;font-size:13px;font-weight:800;line-height:1.6;">${message}</p>
          `,
          showConfirmButton: false,
          timer: 3300,
          timerProgressBar: true
        }).catch(() => fireTerminalAlert({
          icon: 'success',
          title: isOut ? 'Absensi Pulang Tercatat' : 'Absensi Masuk Tercatat',
          text: message,
          showConfirmButton: false,
          timer: 3300,
          timerProgressBar: true
        }));
      } else {
        const message = data.message || 'Scan tidak dapat diproses. Silakan coba lagi.';
        const isWarning = message.toLowerCase().includes('sudah');
        const safeMessage = escapeHtml(message);

        RfidAlert.fire({
          icon: isWarning ? 'warning' : 'error',
          title: isWarning ? 'Absensi Sudah Tercatat' : 'Scan Tidak Berhasil',
          html: `<p style="margin:0;color:#94a3b8;font-weight:700;line-height:1.6;">${safeMessage}</p>`,
          confirmButtonText: 'Mengerti'
        }).catch(() => fireTerminalAlert({
          icon: isWarning ? 'warning' : 'error',
          title: isWarning ? 'Absensi Sudah Tercatat' : 'Scan Tidak Berhasil',
          text: safeMessage,
          confirmButtonText: 'Mengerti'
        }));
      }
    }

    function updateActivityRow(data) {
      if (emptyState) emptyState.remove();

      const existingRow = activityBody.querySelector(`[id="row-${data.user_id}"]`);
      
      if (data.type === 'out' && existingRow) {
        existingRow.querySelector('.time-out').textContent = data.time;
        existingRow.classList.add('bg-blue-500/5');
        setTimeout(() => existingRow.classList.remove('bg-blue-500/5'), 2000);
        return;
      }

      const row = document.createElement('tr');
      row.id = `row-${data.user_id || Date.now()}`;
      row.className = 'activity-row group border-b border-white/5 hover:bg-white/[0.03] transition-colors';
      row.innerHTML = `
        <td class="py-5 px-8">
          <div class="flex flex-col">
            <span class="font-bold text-slate-300 group-hover:text-slate-100 transition-colors">${data.user}</span>
            <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest mt-1">${data.kelas || '-'} / ${data.jurusan || '-'}</span>
          </div>
        </td>
        <td class="py-5 px-6 text-slate-400 text-sm font-bold time-in">
          ${data.time}
        </td>
        <td class="py-5 px-6 text-slate-400 text-sm font-bold time-out">
          --:--
        </td>
        <td class="py-5 px-8 text-right">
          <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
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
