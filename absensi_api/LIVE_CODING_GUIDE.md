# 🚀 Panduan Survival Live Coding (Persiapan Ujikom)

Jangan panik! Biasanya penguji hanya minta perubahan **ringan** untuk mengetes apakah Anda benar-benar paham kode Anda. Berikut adalah "resep" untuk menghadapi permintaan live coding yang paling umum.

---

## 🛠️ Skenario 1: "Tolong ganti teks/warna di halaman X"
**Langkah-langkah:**
1.  **Cari View-nya:** Semua tampilan ada di folder `resources/views/`.
2.  **Ganti Teks:** Cari teks yang ingin diganti, langsung ketik perubahannya.
3.  **Ganti Warna (Tailwind):** 
    *   Biru: `bg-blue-600` / `text-blue-600`
    *   Merah: `bg-red-600` / `text-red-600`
    *   Hijau: `bg-emerald-600` / `text-emerald-600`
4.  **Refresh Browser.**

---

## 🛡️ Skenario 2: "Tolong tambah validasi, misal rfid_uid tidak boleh kosong"
**Langkah-langkah:**
1.  Buka **Controller** yang bersangkutan (misal `RfidController.php`).
2.  Cari bagian `$request->validate([...])`.
3.  Tambahkan aturan baru. Contoh: `'nama_field' => 'required|min:5'`.
    *   `required`: Wajib diisi.
    *   `unique:tabel,kolom`: Tidak boleh sama dengan data lain.
    *   `min:8`: Minimal 8 karakter.

---

## 🗄️ Skenario 3: "Tolong tampilkan data baru di tabel, misal kolom 'Metode'"
**Langkah-langkah:**
1.  Buka file **View** tabel tersebut (misal `dashboard.blade.php`).
2.  Tambah `<th>` baru di bagian `<thead>` (untuk judul kolom).
3.  Tambah `<td>` baru di bagian `<tbody>` di dalam loop `@foreach`.
4.  Panggil datanya: `{{ $item->method }}`.

---

## 🚦 Skenario 4: "Bagaimana cara membatasi akses agar halaman X hanya untuk Admin?"
**Langkah-langkah:**
1.  Buka `routes/web.php`.
2.  Pindahkan rute tersebut ke dalam grup middleware `auth`.
3.  Di dalam Controller, tambahkan pengecekan manual (jika diminta):
```php
if (auth()->user()->role !== 'admin') {
    abort(403, 'Anda bukan admin!');
}
```

---

## 🔄 Rumus Cepat Menghadapi Pertanyaan "Coba Ubah Ini..."
Jika penguji minta perubahan, ikuti pola pikir **F-C-R**:
1.  **F (Find):** Cari filenya.
    *   Urusan tampilan? Lihat `resources/views`.
    *   Urusan simpan/proses data? Lihat `app/Http/Controllers`.
    *   Urusan rute/URL? Lihat `routes/web.php`.
2.  **C (Change):** Lakukan perubahan kecil. Jangan takut salah, Laravel akan kasih error yang jelas di mana letak salahnya.
3.  **R (Refresh):** Simpan file (Ctrl+S) dan cek hasilnya di browser.

---

## 💡 Tips Rahasia:
*   **Gunakan Ctrl + P:** Di VS Code, tekan `Ctrl + P` lalu ketik nama file untuk pindah file dengan cepat tanpa harus nyari di folder.
*   **Baca Pesan Error:** Kalau muncul halaman merah (error), baca bagian atasnya. Biasanya Laravel kasih tahu: *"Property [nama] does not exist"* artinya Anda salah ketik nama kolom.
*   **Tenang:** Penguji lebih menghargai siswa yang tahu **di mana** harus merubah kode daripada siswa yang hafal kode tapi bingung naruhnya di mana.

---
*Fokus, tenang, dan tunjukkan Anda adalah nahkoda di project Anda sendiri!*
