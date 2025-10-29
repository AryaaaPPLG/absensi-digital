<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{
       public function showAbsensi()
    {
        return view('absensi');
    }
    public function faceAttendance(Request $request)
{
    $response = Http::attach(
    'image', file_get_contents($request->file('image')), 'face.jpg'
)->post('http://127.0.0.1:5000/api/recognize-face');

    $data = $response->json();

    if (isset($data['message']) && $data['message'] === 'Wajah cocok') {
        \App\Models\Attendance::create([
            'username' => $data['username'],
            'time' => now(),
            'status' => 'Hadir',
        ]);

        return response()->json([
            'message' => 'Absensi berhasil dicatat',
            'username' => $data['username'],
            'time' => now()->toDateTimeString(),
        ]);
    }

    return response()->json([
        'message' => 'Wajah tidak dikenali',
        'time' => now()->toDateTimeString(),
    ]);
}
    
}
