<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class DailyAttendanceRecap extends Command
{
    /**
     * The name and signature of the console command.
     * Nama perintah yang dijalankan di terminal (contoh: php artisan attendance:recap-daily)
     * @var string
     */
    protected $signature = 'attendance:recap-daily';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Automatically mark users who did not attend today as Alpha at the end of the day';

    /**
     * Execute the console command.
     * Fungsi handle() berisi logika utama yang dijalankan saat perintah dipanggil.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $this->info("Starting daily recap for $today...");

        // 1. Ambil semua user yang memiliki role 'siswa' atau 'guru'
        // Kita mengabaikan Admin karena Admin tidak perlu diabsen Alpha.
        $users = User::whereIn('role', ['siswa', 'guru'])->get();
        $count = 0;

        foreach ($users as $user) {
            // 2. Cek apakah user ini sudah punya record absensi hari ini di tabel 'attendances'
            $exists = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->exists();

            // 3. JIKA TIDAK ADA DATA (Berarti dia tidak tap kartu seharian)
            if (!$exists) {
                // Buat record absensi baru dengan status 'alpha'
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'status' => 'alpha',
                    'time_in' => null, // Jam masuk dikosongkan
                    'method' => 'system' // Metode dicatat sebagai 'system' karena dibuat otomatis oleh server
                ]);
                $count++;
            }
        }

        $this->info("Recap completed. $count users marked as Alpha.");
    }
}
