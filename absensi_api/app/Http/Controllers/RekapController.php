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
        
        $siswa = User::where('role', 'siswa')->orderBy('name')->get();
        $attendances = Attendance::whereDate('date', $date)->get()->keyBy('user_id');

        $rekap = $siswa->map(function ($user) use ($attendances, $date) {
            $att = $attendances->get($user->id);
            return (object) [
                'user_id' => $user->id,
                'name' => $user->name,
                'kelas' => $user->kelas,
                'jurusan' => $user->jurusan,
                'status' => $att ? $att->status : 'alpha',
                'time_in' => $att ? $att->time_in : '-',
                'date' => $date
            ];
        });

        return view('rekap.index', compact('rekap', 'date'));
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

        Attendance::updateOrCreate(
            ['user_id' => $request->user_id, 'date' => $request->date],
            [
                'status' => $request->status, 
                'time_in' => ($request->status == 'hadir' || $request->status == 'terlambat') && !$request->time_in ? now()->toTimeString() : $request->time_in
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
        $siswa = User::where('role', 'siswa')->get();

        foreach ($siswa as $user) {
            Attendance::updateOrCreate(
                ['user_id' => $user->id, 'date' => $date],
                ['status' => 'hadir', 'time_in' => '07:00:00', 'method' => 'manual']
            );
        }

        return back()->with('success', 'Semua siswa berhasil ditandai hadir.');
    }

    public function export(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'guru'])) {
            abort(403);
        }

        $date = $request->input('date', Carbon::today()->toDateString());
        $siswa = User::where('role', 'siswa')->orderBy('name')->get();
        $attendances = Attendance::whereDate('date', $date)->get()->keyBy('user_id');

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=rekap-absensi-$date.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($siswa, $attendances, $date) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama', 'Kelas', 'Jurusan', 'Tanggal', 'Jam Masuk', 'Status']);

            foreach ($siswa as $index => $user) {
                $att = $attendances->get($user->id);
                fputcsv($file, [
                    $index + 1,
                    $user->name,
                    $user->kelas,
                    $user->jurusan,
                    $date,
                    $att ? $att->time_in : '-',
                    $att ? strtoupper($att->status) : 'ALPHA'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
