<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Events\AttendanceScanned;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
     */
    public function register(Request $request)
    {
        $request->validate([
            'rfid_uid' => 'required|string|unique:users,rfid_uid',
        ]);

        $user = auth()->user();
        $user->rfid_uid = $request->rfid_uid;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'RFID Card successfully registered!');
    }

    /**
     * Handle RFID scanning for attendance.
     * This endpoint will likely be called by an external RFID reader device.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'rfid_uid' => 'required|string',
        ]);

        $user = User::with('schoolClass')->where('rfid_uid', $request->rfid_uid)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'RFID Card not recognized. Please register your card first.'
            ], 404);
        }

        $today = Carbon::today();
        
        // Check if user already scanned today
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance) {
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

            // Check if clock out is allowed (Gate check)
            if (\App\Models\Config::get('allow_clock_out', '0') !== '1') {
                return response()->json([
                    'success' => false,
                    'message' => 'Gerbang absensi pulang belum dibuka oleh admin.',
                    'user' => $user->name,
                    'user_id' => $user->id,
                    'time_in' => $attendance->time_in,
                ], 403);
            }

            $attendance->update([
                'time_out' => Carbon::now()->toTimeString()
            ]);

            // Trigger Real-time Event
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

        // Record attendance
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'time_in' => Carbon::now()->toTimeString(),
            'status' => 'hadir',
            'method' => 'rfid',
        ]);

        // Trigger Real-time Event
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
