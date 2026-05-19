<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin Dashboard Logic
        if ($user->role === 'admin') {
            $stats = [
                'total_users' => User::count(),
                'total_guru' => User::where('role', 'guru')->count(),
                'total_siswa' => User::where('role', 'siswa')->count(),
                'attendance_today' => Attendance::whereDate('date', Carbon::today())->count(),
                'terlambat_today' => Attendance::whereDate('date', Carbon::today())->where('status', 'terlambat')->count(),
                'recent_attendances' => Attendance::with('user')
                    ->latest()
                    ->take(10)
                    ->get()
            ];

            return view('dashboard', compact('user', 'stats'));
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

        return view('dashboard', compact('user', 'myStats'));
    }
}
