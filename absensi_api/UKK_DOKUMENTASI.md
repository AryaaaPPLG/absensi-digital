# 🎓 Dokumentasi Teknis Project Absensi Digital RFID (UKK)

Dokumen ini berisi penjelasan mengenai file-file penting dan potongan kode utama yang digunakan dalam sistem Absensi Digital RFID ini. Dokumentasi ini disusun untuk mempermudah penjelasan saat Ujian Kompetensi Keahlian (UKK).

---

## 📂 1. Arsitektur File Penting

Berikut adalah daftar file yang menjadi "otak" dari sistem ini:

| Lokasi File | Fungsi Utama |
| :--- | :--- |
| `app/Http/Controllers/RfidController.php` | Mengatur logika scan kartu RFID dan registrasi kartu. |
| `app/Http/Controllers/AiInsightController.php` | Menghasilkan ringkasan laporan menggunakan AI (Google Gemini). |
| `app/Console/Commands/DailyAttendanceRecap.php` | Otomatisasi sistem untuk menandai siswa "Alpha" di akhir hari. |
| `app/Models/Attendance.php` | Model database untuk menyimpan data kehadiran. |
| `routes/api.php` | Jalur (route) yang digunakan oleh Terminal/Alat untuk mengirim data scan. |
| `resources/views/absensi.blade.php` | Antarmuka (UI) Terminal Scan yang menunggu input dari kartu. |

---

## 📟 2. Logika Utama: Pemrosesan Scan RFID

Bagian ini adalah bagian terpenting karena menjelaskan bagaimana kartu RFID yang ditempelkan bisa tercatat sebagai absensi.

### A. Frontend (Scanner Interface)
File: `resources/views/absensi.blade.php`

Sistem menggunakan **Hidden Input** yang selalu fokus agar saat kartu ditempelkan (yang mengirimkan teks UID), sistem langsung menangkapnya.

```javascript
// Menangkap input dari RFID Reader
rfidInput.addEventListener('keypress', async (e) => {
  if (e.key === 'Enter') {
    const uid = rfidInput.value.trim();
    if (uid) processScan(uid); // Kirim ke server
    rfidInput.value = ''; // Kosongkan input kembali
  }
});

async function processScan(uid) {
  const response = await fetch('/api/attendance/scan', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ rfid_uid: uid })
  });
  // Tampilkan notifikasi Berhasil/Gagal
}
```

### B. Backend (Logika Server)
File: `app/Http/Controllers/RfidController.php`

Fungsi `scan()` menangani validasi kartu, pengecekan waktu masuk, dan pengecekan waktu pulang.

```php
public function scan(Request $request)
{
    // 1. Cari user berdasarkan UID kartu
    $user = User::where('rfid_uid', $request->rfid_uid)->first();

    if (!$user) {
        return response()->json(['message' => 'Kartu tidak terdaftar'], 404);
    }

    $today = Carbon::today();
    $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

    // 2. Jika sudah absen masuk, maka proses absen PULANG
    if ($attendance) {
        if ($attendance->time_out) {
            return response()->json(['message' => 'Sudah absen masuk & pulang'], 400);
        }

        $attendance->update(['time_out' => Carbon::now()->toTimeString()]);
        return response()->json(['type' => 'out', 'message' => 'Berhasil Pulang']);
    }

    // 3. Jika belum ada data hari ini, proses absen MASUK
    $attendance = Attendance::create([
        'user_id' => $user->id,
        'date' => $today,
        'time_in' => Carbon::now()->toTimeString(),
        'status' => 'hadir',
        'method' => 'rfid',
    ]);

    return response()->json(['type' => 'in', 'message' => 'Berhasil Masuk']);
}
```

---

## 🤖 3. Fitur Unggulan: AI Insight (Analisis Kehadiran)
File: `app/Http/Controllers/AiInsightController.php`

Fitur ini menggunakan API Google Gemini untuk menganalisis data absensi secara otomatis dan memberikan saran manajemen.

```php
$prompt = "Berikut adalah data absensi hari ini: Total hadir {$total}, Terlambat {$terlambat}. Berikan ringkasan profesional...";

$response = Http::post("https://generativelanguage.googleapis.com/.../gemini-flash-lite-latest:generateContent?key=$apiKey", [
    'contents' => [['parts' => [['text' => $prompt]]]]
]);
```

---

## ⏰ 4. Otomatisasi: Penandaan Alpha Otomatis
File: `app/Console/Commands/DailyAttendanceRecap.php`

Fitur ini berjalan di background (scheduler) untuk memastikan siswa yang tidak melakukan tap kartu akan otomatis dianggap "Alpha" di akhir hari.

```php
public function handle()
{
    $users = User::whereIn('role', ['siswa'])->get();
    foreach ($users as $user) {
        $exists = Attendance::where('user_id', $user->id)->whereDate('date', $today)->exists();
        if (!$exists) {
            Attendance::create([
                'user_id' => $user->id,
                'status' => 'alpha',
                'method' => 'system'
            ]);
        }
    }
}
```

---

## 📝 5. Fitur Activity Log
File: `app/Models/ActivityLog.php`

Setiap ada kejadian penting (registrasi kartu, perubahan config), sistem akan mencatatnya di database agar bisa dipantau di Dashboard.

```php
public static function log($activity, $description = null, $icon = 'fa-info-circle', $color = 'blue')
{
    return self::create([
        'user_id' => auth()->id(),
        'activity' => $activity,
        'description' => $description,
        'icon' => $icon,
        'color' => $color
    ]);
}
```

---
*Dibuat untuk keperluan Ujian Kompetensi Keahlian (UKK) - Sistem Absensi Digital RFID.*
