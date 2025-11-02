<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Kalau belum daftar wajah, arahkan ke register face
    $hasFace = \App\Models\FaceEncoding::where('user_id', $user->id)->exists();

    if (!$hasFace) {
        return redirect('/register-face')->with('info', 'Silakan daftarkan wajah Anda terlebih dahulu.');
    }

    return view('dashboard', compact('user'));
}

}
