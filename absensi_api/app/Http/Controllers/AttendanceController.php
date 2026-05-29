<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Config;
use App\Events\AttendanceScanned;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showAbsensi()
    {
        $recentAttendances = Attendance::with('user.schoolClass')
            ->whereDate('date', Carbon::today())
            ->latest()
            ->take(10)
            ->get();

        return view('absensi', compact('recentAttendances'));
    }

    public function getRealtimeStats()
    {
        $today = Carbon::today();
        
        $stats = [
            'hadir' => Attendance::whereDate('date', $today)->where('status', 'hadir')->count(),
            'terlambat' => Attendance::whereDate('date', $today)->where('status', 'terlambat')->count(),
            'izin' => Attendance::whereDate('date', $today)->where('status', 'izin')->count(),
            'alpha' => Attendance::whereDate('date', $today)->where('status', 'alpha')->count(),
        ];

        $recentAttendances = Attendance::with('user.schoolClass')
            ->whereDate('date', $today)
            ->latest()
            ->take(10)
            ->get()
            ->map(function($att) {
                return [
                    'id' => $att->id,
                    'user_name' => $att->user->name,
                    'user_id' => $att->user->id,
                    'kelas' => $att->user->schoolClass?->nama_kelas,
                    'jurusan' => $att->user->schoolClass?->jurusan,
                    'time_in' => $att->time_in,
                    'status' => $att->status,
                    'method' => 'RFID',
                    'date' => $att->date->format('d M Y'),
                    'avatar' => "https://ui-avatars.com/api/?name=" . urlencode($att->user->name) . "&background=3b82f6&color=fff"
                ];
            });

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'recent' => $recentAttendances
        ]);
    }
}
