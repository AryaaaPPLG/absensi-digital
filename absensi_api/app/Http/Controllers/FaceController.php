<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaceController extends Controller
{
    public function showRegisterFace()
    {
        return view('auth.register-face');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $faceImage = $request->input('face_image');

        // Simpan base64 image ke database (atau bisa juga disimpan ke file)
        $user->face_encoding = $faceImage;
        $user->face_registered = true;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Wajah berhasil didaftarkan!');
    }
}
