# 📘 Panduan Pembahasan Ujikom (Kelas XI)

Dokumen ini adalah "Cheat Sheet" untuk membantu Anda menjawab pertanyaan penguji berdasarkan **Kisi-kisi Ujikom Kelas Industri**.

---

## 🏗️ 1. Arsitektur & Konfigurasi Framework (MVC)
**Pertanyaan:** "Apa itu MVC dan bagaimana implementasinya di project ini?"
*   **Jawaban:** MVC adalah Model-View-Controller.
    *   **Model:** Mengurus database (Contoh: `app/Models/User.php`).
    *   **View:** Mengurus tampilan/UI (Contoh: `resources/views/absensi.blade.php`).
    *   **Controller:** Mengurus logika bisnis/penghubung (Contoh: `app/Http/Controllers/RfidController.php`).
*   **Poin Tambahan:** File `.env` digunakan untuk menyimpan data sensitif seperti password database dan API Key Google Gemini agar aman.

---

## 🛣️ 2. Routing & Logic Handling
**Pertanyaan:** "Di mana rute didaftarkan dan bagaimana cara kerja Controller?"
*   **Jawaban:** Rute didaftarkan di `routes/web.php` dan `routes/api.php`.
*   **Logic Handling:** Kita menggunakan Controller untuk memproses data. Contohnya, saat user tap kartu, rute memanggil fungsi `scan()` di `RfidController`.
*   **Blade Templating:** Kita menggunakan `@extends` untuk mewarisi layout utama dan `@yield` untuk menentukan di mana konten halaman anak akan ditampilkan.

---

## 🗄️ 3. Database, Migration, & Eloquent ORM
**Pertanyaan:** "Bagaimana Anda mengelola database dan relasi antar tabel?"
*   **Migration:** Kita membuat tabel menggunakan perintah `php artisan make:migration`. Ini seperti version control untuk database.
*   **Eloquent ORM:** Kita tidak menulis SQL manual, tapi menggunakan Eloquent.
    *   `all()`: Ambil semua data.
    *   `find()` / `first()`: Cari satu data.
    *   `create()`: Simpan data baru.
    *   `update()`: Ubah data.
*   **Relasi Tabel:**
    *   `belongsTo`: Contoh User dimiliki oleh satu Kelas (`User -> belongsTo -> SchoolClass`).
    *   `hasMany`: Contoh satu User punya banyak catatan kehadiran (`User -> hasMany -> Attendance`).

---

## 🛡️ 4. Keamanan Sistem & Validasi Input
**Pertanyaan:** "Bagaimana aplikasi Anda menangani keamanan?"
*   **SQL Injection:** Eloquent secara otomatis memproteksi dari SQL Injection.
*   **CSRF Protection:** Setiap Form wajib menggunakan `@csrf` agar tidak bisa dimanipulasi dari situs lain.
*   **Hashing Password:** Kita menggunakan `Hash::make()` atau cast `hashed` di model User agar password tidak disimpan dalam bentuk teks biasa.
*   **Validation Rules:** Di Controller, kita menggunakan `$request->validate()` untuk memastikan data yang masuk sudah benar (misalnya email harus unik).

---

## 🚦 5. Middleware & Request Lifecycle
**Pertanyaan:** "Apa fungsi Middleware dan bagaimana alur data dari Request ke Response?"
*   **Middleware:** Kita menggunakan middleware `auth` untuk memproteksi halaman Dashboard. Jika user belum login, dia akan ditendang kembali ke halaman Login.
*   **Request Lifecycle:** 
    1.  User klik tombol/tap kartu (Request).
    2.  Route mengarahkan ke Controller.
    3.  Controller meminta data ke Model.
    4.  Model mengambil data dari Database.
    5.  Controller mengirim data ke View.
    6.  View ditampilkan ke User (Response).

---

## 📟 6. Fokus Khusus: RFID Scanning
**Pertanyaan:** "Jelaskan alur scan kartu RFID!"
*   **Frontend:** Ada input tersembunyi yang selalu *focus*. Scanner RFID mengetik UID kartu ke input tersebut.
*   **Backend:** Controller menerima UID, mencari user di database, lalu mengecek apakah user tersebut sudah absen masuk atau belum. Jika belum, buat record Masuk. Jika sudah, update record tersebut untuk waktu Pulang.

---
*Dibuat untuk mempermudah belajar Ujikom Kelas XI RPL.*
