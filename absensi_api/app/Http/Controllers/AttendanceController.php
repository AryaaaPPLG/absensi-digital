<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showAbsensi()
    {
        $recentAttendances = Attendance::with('user')
            ->whereDate('date', Carbon::today())
            ->latest()
            ->take(10)
            ->get();

        return view('absensi', compact('recentAttendances'));
    }

    public function faceAttendance(Request $request)
    {
        $response = Http::attach(
            'image', file_get_contents($request->file('image')), 'face.jpg'
        )->post('http://127.0.0.1:5000/api/recognize-face');

        $data = $response->json();

        if (isset($data['message']) && $data['message'] === 'Wajah cocok') {
            $user = User::where('username', $data['username'])->first();
            
            if ($user) {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => now()->toDateString(),
                    'time_in' => now()->toTimeString(),
                    'status' => 'hadir',
                    'method' => 'face',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Absensi berhasil dicatat',
                    'user' => $user->name,
                    'kelas' => $user->kelas,
                    'jurusan' => $user->jurusan,
                    'time' => now()->toTimeString(),
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Wajah tidak dikenali atau pengguna tidak ditemukan',
            'time' => now()->toDateTimeString(),
        ], 404);
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

        $recentAttendances = Attendance::with('user')
            ->whereDate('date', $today)
            ->latest()
            ->take(10)
            ->get()
            ->map(function($att) {
                return [
                    'id' => $att->id,
                    'user_name' => $att->user->name,
                    'user_id' => $att->user->id,
                    'kelas' => $att->user->kelas,
                    'jurusan' => $att->user->jurusan,
                    'time_in' => $att->time_in,
                    'status' => $att->status,
                    'method' => $att->method ?? 'RFID',
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
