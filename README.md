# 📘 Dokumentasi Sistem Absensi Digital RFID

Selamat datang di panduan penggunaan **Sistem Absensi Digital RFID**. Sistem ini dirancang untuk mempermudah pencatatan kehadiran menggunakan teknologi kartu RFID secara real-time, akurat, dan transparan.

---

## 🚀 1. Halaman Utama (Landing Page)
Halaman ini adalah wajah pertama sistem yang bisa diakses oleh siapa saja.
- **Monitor Real-time:** Menampilkan daftar siswa yang baru saja melakukan scan kartu di sekolah.
- **Statistik Cepat:** Menampilkan tingkat akurasi sistem dan kecepatan pemrosesan data.
- **Navigasi Cepat:** Tombol untuk masuk ke Dashboard, Terminal Scan, atau pendaftaran akun.

## 📠 2. Terminal RFID (Scanner)
Ini adalah halaman khusus yang biasanya dibuka di layar monitor dekat pintu masuk/gerbang.
- **Cara Absensi:** Cukup tempelkan kartu RFID pada reader. Sistem akan otomatis mengenali Anda.
- **Feedback Visual:** 
  - **Berhasil:** Muncul notifikasi hijau dengan nama, kelas, dan waktu Anda.
  - **Gagal/Peringatan:** Muncul notifikasi jika kartu tidak dikenal atau Anda sudah melakukan absen sebelumnya.
- **Mode Pulang:** Jika admin sudah membuka "Absensi Pulang", maka scan kartu akan mencatat waktu keluar Anda.

## 👤 3. Sistem Akun (Login & Daftar)
- **Registrasi:** Siswa dan Guru bisa mendaftar mandiri dengan mengisi Nama, Username, Email, Kelas, dan Jurusan.
- **Login:** Masuk menggunakan Username dan Password yang didaftarkan.
- **Update Kartu:** Setelah mendaftar, pengguna wajib mendaftarkan ID kartu RFID mereka di dashboard agar kartu bisa digunakan untuk absen.

## 🛠️ 4. Dashboard Admin
Panel kendali penuh untuk pengelola sistem.
- **Statistik Total:** Melihat jumlah Guru, Siswa, dan total kehadiran hari ini secara visual.
- **System Log (Terbaru!):** Memantau aktivitas sistem seperti "Siapa yang baru mendaftar" atau "Kapan admin membuka gerbang pulang".
- **Quick Actions:** 
  - **Toggle Pulang:** Membuka/menutup akses absensi keluar (pulang).
  - **AI Overlord Insight:** Analisis pintar terhadap data absensi untuk melihat pola perilaku (misal: siapa yang sering terlambat).
  - **Manajemen User:** Mengelola data pengguna (tambah, edit, hapus, atau ganti role).

## 👨‍🎓 5. Dashboard Siswa & Guru
Halaman pribadi untuk setiap pengguna.
- **Performa Bulanan:** Statistik berapa kali Hadir, Terlambat, Izin, atau Alpha dalam bulan berjalan.
- **Riwayat Kehadiran:** Daftar 5 aktivitas terakhir (jam masuk dan jam pulang).
- **Update Kartu RFID:** Menu mandiri untuk mendaftarkan ID kartu baru jika kartu lama hilang atau ingin diganti.

## 📊 6. Fitur Rekap & Laporan
- **Rekap Harian/Bulanan:** Guru dan Admin dapat melihat laporan lengkap kehadiran dalam periode tertentu.
- **Status Otomatis:** Sistem memiliki fitur rekap harian otomatis yang akan menandai pengguna sebagai "Alpha" jika tidak ada catatan kehadiran hingga jam yang ditentukan.

## 🤖 7. AI Overlord Insight (Fitur Pintar)
Menggunakan kecerdasan buatan untuk memberikan ringkasan perilaku:
- Mendeteksi tren keterlambatan.
- Memberikan saran tindakan bagi admin.
- Menganalisis tingkat kedisiplinan secara keseluruhan.

---
**💡 Tips Cepat:**
1. Pastikan koneksi internet stabil saat Terminal Scan sedang aktif.
2. Selalu klik **Logout** setelah selesai menggunakan dashboard di perangkat publik.
3. Gunakan font **Plus Jakarta Sans** (sudah terpasang) untuk pengalaman visual terbaik.

---
*Dibuat dengan ❤️ oleh RPL SMEMSA - 2026*
