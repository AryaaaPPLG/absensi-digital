<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isClockOutOpen = Config::get('allow_clock_out', '0') === '1';

        // Admin Dashboard Logic
        if ($user->role === 'admin') {
            $stats = [
                'total_users' => User::count(),
                'total_guru' => User::where('role', 'guru')->count(),
                'total_siswa' => User::where('role', 'siswa')->count(),
                'attendance_today' => Attendance::whereDate('date', Carbon::today())->count(),
                'terlambat_today' => Attendance::whereDate('date', Carbon::today())->where('status', 'terlambat')->count(),
                'recent_attendances' => Attendance::with('user.schoolClass')
                    ->latest()
                    ->take(10)
                    ->get(),
                'system_logs' => \App\Models\ActivityLog::latest()->take(5)->get()
            ];

            return view('dashboard', compact('user', 'stats', 'isClockOutOpen'));
        }

        // Regular User Dashboard Logic
        if (!$user->rfid_uid) {
            return redirect()->route('rfid.register.view')->with('info', 'Silakan daftarkan kartu RFID Anda terlebih dahulu.');
        }

        $myStats = [
            'hadir' => Attendance::where('user_id', $user->id)->where('status', 'hadir')->whereMonth('date', Carbon::now()->month)->count(),
            'terlambat' => Attendance::where('user_id', $user->id)->where('status', 'terlambat')->whereMonth('date', Carbon::now()->month)->count(),
            'izin' => Attendance::where('user_id', $user->id)->where('status', 'izin')->whereMonth('date', Carbon::now()->month)->count(),
            'alpha' => Attendance::where('user_id', $user->id)->where('status', 'alpha')->whereMonth('date', Carbon::now()->month)->count(),
            'history' => Attendance::where('user_id', $user->id)->latest()->take(5)->get()
        ];

        return view('dashboard', compact('user', 'myStats', 'isClockOutOpen'));
    }

    public function toggleClockOut()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $current = Config::get('allow_clock_out', '0');
        $new = $current === '1' ? '0' : '1';
        Config::set('allow_clock_out', $new);

        $status = $new === '1' ? 'DIBUKA' : 'DITUTUP';
        
        \App\Models\ActivityLog::log(
            'Clock Out Toggled',
            "Admin telah {$status} akses absensi pulang.",
            $new === '1' ? 'fa-door-open' : 'fa-door-closed',
            $new === '1' ? 'emerald' : 'rose'
        );

        return back()->with('success', "Gerbang absensi pulang berhasil $status.");
    }

    /**
     * Force run the daily recap command.
     */
    public function forceRecap()
    {
        if (!in_array(Auth::user()->role, ['admin', 'guru'])) {
            abort(403);
        }

        Artisan::call('attendance:recap-daily');
        
        return back()->with('success', 'Rekapitulasi otomatis berhasil dijalankan. Semua absen yang kosong hari ini telah ditandai Alpha.');
    }
}
