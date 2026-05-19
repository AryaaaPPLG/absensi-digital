<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
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

        $user = User::where('rfid_uid', $request->rfid_uid)->first();

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
            return response()->json([
                'success' => false,
                'message' => 'You have already recorded your attendance today.',
                'user' => $user->name,
                'kelas' => $user->kelas,
                'jurusan' => $user->jurusan,
                'time' => $attendance->time_in
            ], 400);
        }

        // Record attendance
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'time_in' => Carbon::now()->toTimeString(),
            'status' => 'hadir',
            'method' => 'rfid',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully!',
            'user' => $user->name,
            'kelas' => $user->kelas,
            'jurusan' => $user->jurusan,
            'time' => $attendance->time_in
        ]);
    }
}
