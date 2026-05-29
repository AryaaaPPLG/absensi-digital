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
     *
     * @var string
     */
    protected $signature = 'attendance:recap-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically mark users who did not attend today as Alpha at the end of the day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $this->info("Starting daily recap for $today...");

        // Get all users who are not admins
        $users = User::whereIn('role', ['siswa', 'guru'])->get();
        $count = 0;

        foreach ($users as $user) {
            // Check if user already has an attendance record for today
            $exists = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->exists();

            if (!$exists) {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'status' => 'alpha',
                    'time_in' => null,
                    'method' => 'system'
                ]);
                $count++;
            }
        }

        $this->info("Recap completed. $count users marked as Alpha.");
    }
}
