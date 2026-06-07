<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Events\AttendanceScanned;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * @KISI-KISI: ARSITEKTUR MVC (Controller)
 * RfidController adalah bagian dari 'Controller' dalam pola MVC.
 * Ia bertugas sebagai jembatan antara Request (User) dan Database (Model).
 */
class RfidController extends Controller
{
    /**
     * Show the RFID registration page.
     */
    public function showRegistrationForm()
    {
        return view('auth.register-rfid');
    }

    /**
     * Store the RFID UID for the authenticated user.
     * Fungsi ini digunakan untuk mendaftarkan ID unik kartu RFID ke akun user.
     */
    public function register(Request $request)
    {
        /**
         * @KISI-KISI: VALIDASI DATA (Validation Rules)
         * Memastikan input 'rfid_uid' ada dan belum pernah dipakai (unique).
         */
        $request->validate([
            'rfid_uid' => 'required|string|unique:users,rfid_uid',
        ]);

        // Ambil data user yang sedang login
        $user = auth()->user();
        $user->rfid_uid = $request->rfid_uid;
        
        /**
         * @KISI-KISI: ELOQUENT ORM (save)
         * Menggunakan metode save() untuk menyimpan perubahan ke database.
         */
        $user->save();

        // Catat aktivitas registrasi kartu ke dalam log
        \App\Models\ActivityLog::log(
            'RFID Card Registered',
            "Pengguna {$user->name} telah mendaftarkan kartu RFID baru.",
            'fa-id-card',
            'indigo'
        );

        return redirect()->route('dashboard')->with('success', 'RFID Card successfully registered!');
    }

    /**
     * Handle RFID scanning for attendance.
     * Fungsi utama untuk memproses tap kartu RFID di terminal absensi.
     */
    public function scan(Request $request)
    {
        // Pastikan UID kartu dikirim dalam request
        $request->validate([
            'rfid_uid' => 'required|string',
        ]);

        /**
         * @KISI-KISI: ELOQUENT ORM (SELECT Query)
         * Mencari user berdasarkan rfid_uid menggunakan metode 'where' dan 'first'.
         */
        $user = User::with('schoolClass')->where('rfid_uid', $request->rfid_uid)->first();

        // Jika kartu tidak ditemukan/tidak terdaftar, kirim respon error 404
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'RFID Card not recognized. Please register your card first.'
            ], 404);
        }

        $today = Carbon::today();
        
        /**
         * @KISI-KISI: ELOQUENT ORM (Pengecekan Data)
         * Cek apakah user ini sudah melakukan absensi hari ini.
         */
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // JIKA SUDAH ABSEN (Artinya proses selanjutnya adalah Absen Pulang)
        if ($attendance) {
            // Jika sudah ada jam pulang, berarti dia sudah tap 2x sebelumnya
            if ($attendance->time_out) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absensi masuk dan pulang hari ini.',
                    'user' => $user->name,
                    'user_id' => $user->id,
                    'time_in' => $attendance->time_in,
                    'time_out' => $attendance->time_out
                ], 400);
            }

            // Validasi: Cek apakah Admin sudah mengizinkan/membuka akses untuk pulang
            if (\App\Models\Config::get('allow_clock_out', '0') !== '1') {
                return response()->json([
                    'success' => false,
                    'message' => 'Gerbang absensi pulang belum dibuka oleh admin.',
                    'user' => $user->name,
                    'user_id' => $user->id,
                    'time_in' => $attendance->time_in,
                ], 403);
            }

            /**
             * @KISI-KISI: ELOQUENT ORM (update)
             * Memperbarui data kolom time_out.
             */
            $attendance->update([
                'time_out' => Carbon::now()->toTimeString()
            ]);

            // Trigger Event Real-time: Agar dashboard/layar terminal langsung terupdate otomatis
            event(new AttendanceScanned($attendance));

            return response()->json([
                'success' => true,
                'message' => 'Absensi pulang berhasil dicatat!',
                'user' => $user->name,
                'user_id' => $user->id,
                'kelas' => $user->schoolClass?->nama_kelas,
                'jurusan' => $user->schoolClass?->jurusan,
                'time' => $attendance->time_out,
                'type' => 'out'
            ]);
        }

        /**
         * @KISI-KISI: ELOQUENT ORM (create)
         * Menambahkan data baru ke tabel attendances (Absen Masuk).
         */
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'time_in' => Carbon::now()->toTimeString(),
            'status' => 'hadir',
            'method' => 'rfid',
        ]);

        // Trigger Event Real-time untuk notifikasi di layar
        event(new AttendanceScanned($attendance));

        return response()->json([
            'success' => true,
            'message' => 'Absensi masuk berhasil dicatat!',
            'user' => $user->name,
            'user_id' => $user->id,
            'kelas' => $user->schoolClass?->nama_kelas,
            'jurusan' => $user->schoolClass?->jurusan,
            'time' => $attendance->time_in,
            'type' => 'in'
        ]);
    }
}
