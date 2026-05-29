<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceApiController extends Controller
{
    // POST /api/attendance/record
    public function record(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'method' => 'nullable|string',
            'status' => 'nullable|in:hadir,alpha,izin,terlambat',
            'meta' => 'nullable|array',
        ]);

        $userId = $request->input('user_id');
        $method = $request->input('method', 'rfid');
        $status = $request->input('status', 'hadir');

        $date = Carbon::today()->toDateString();

        $exists = Attendance::where('user_id', $userId)->whereDate('date', $date)->exists();
        if ($exists) {
            return response()->json(['error' => 'already_exists'], 409);
        }

        $attendance = Attendance::create([
            'user_id' => $userId,
            'date' => $date,
            'time_in' => Carbon::now()->toTimeString(),
            'status' => $status,
            'method' => $method,
            'meta' => $request->input('meta'),
        ]);

        return response()->json(['success' => true, 'attendance' => $attendance], 200);
    }
}
