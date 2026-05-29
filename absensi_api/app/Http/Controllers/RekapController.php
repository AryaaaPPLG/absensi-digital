<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Only Admin and Guru can access
        if (!in_array(Auth::user()->role, ['admin', 'guru'])) {
            abort(403, 'Unauthorized action.');
        }

        $date = $request->input('date', Carbon::today()->toDateString());
        $selectedRole = $request->input('role', 'siswa');
        
        $users = User::with('schoolClass')->where('role', $selectedRole)->orderBy('name')->get();
        $attendances = Attendance::whereDate('date', $date)->get()->keyBy('user_id');

        $rekap = $users->map(function ($user) use ($attendances, $date) {
            $att = $attendances->get($user->id);
            return (object) [
                'user_id' => $user->id,
                'name' => $user->name,
                'kelas' => $user->schoolClass?->nama_kelas,
                'jurusan' => $user->schoolClass?->jurusan,
                'status' => $att ? $att->status : 'alpha',
                'time_in' => $att ? $att->time_in : '-',
                'time_out' => $att ? ($att->time_out ?? '-') : '-',
                'date' => $date
            ];
        });

        return view('rekap.index', compact('rekap', 'date', 'selectedRole'));
    }

    public function update(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'guru'])) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:hadir,alpha,izin,terlambat'
        ]);

        $date = Carbon::parse($request->date)->format('Y-m-d');
        
        // Find existing attendance to preserve time_in if it exists
        $attendance = Attendance::where('user_id', $request->user_id)
            ->whereDate('date', $date)
            ->first();

        $time_in = $attendance ? $attendance->time_in : null;
        
        // If changing to hadir/terlambat and no time_in exists, set current time
        if (in_array($request->status, ['hadir', 'terlambat']) && !$time_in) {
            $time_in = now()->toTimeString();
        } elseif (in_array($request->status, ['alpha', 'izin'])) {
            $time_in = null;
        }

        Attendance::updateOrCreate(
            ['user_id' => $request->user_id, 'date' => $date],
            [
                'status' => $request->status, 
                'time_in' => $time_in,
                'method' => $attendance->method ?? 'manual'
            ]
        );

        return back()->with('success', 'Status absensi berhasil diperbarui.');
    }

    public function bulkHadir(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'guru'])) {
            abort(403);
        }

        $date = $request->input('date', Carbon::today()->toDateString());
        $role = $request->input('role', 'siswa');
        $users = User::where('role', $role)->get();

        foreach ($users as $user) {
            Attendance::updateOrCreate(
                ['user_id' => $user->id, 'date' => $date],
                ['status' => 'hadir', 'time_in' => '07:00:00', 'method' => 'manual']
            );
        }

        return back()->with('success', 'Semua ' . ($role == 'siswa' ? 'siswa' : 'guru') . ' berhasil ditandai hadir.');
    }

    public function export(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'guru'])) {
            abort(403);
        }

        $date = $request->input('date', Carbon::today()->toDateString());
        $role = $request->input('role', 'siswa');
        $users = User::with('schoolClass')->where('role', $role)->orderBy('name')->get();
        $attendances = Attendance::whereDate('date', $date)->get()->keyBy('user_id');

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=rekap-absensi-{$role}-{$date}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($users, $attendances, $date, $role) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama', 'Role', 'Kelas', 'Jurusan', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status']);

            foreach ($users as $index => $user) {
                $att = $attendances->get($user->id);
                fputcsv($file, [
                    $index + 1,
                    $user->name,
                    strtoupper($user->role),
                    $user->schoolClass?->nama_kelas,
                    $user->schoolClass?->jurusan,
                    $date,
                    $att ? $att->time_in : '-',
                    $att ? ($att->time_out ?? '-') : '-',
                    $att ? strtoupper($att->status) : 'ALPHA'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
