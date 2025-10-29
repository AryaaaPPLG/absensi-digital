<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FaceController extends Controller
{
    public function showRegister()
    {
        return response()->json(['message' => 'Form registrasi wajah siap']);
    }

   public function registerFace(Request $request)
{
    // Validasi dulu
    $request->validate([
        'username' => 'required|string',
        'image' => 'required|file|image|max:5120', // max 5MB
    ]);

    // Ambil file image
    $imagePath = $request->file('image')->getRealPath();

    // Kirim ke Flask pakai multipart/form-data
    $response = Http::attach(
        'image', file_get_contents($imagePath), $request->file('image')->getClientOriginalName()
    )->post('http://127.0.0.1:5000/api/register-face', [
        'username' => $request->username,
    ]);

    return $response->json();
}
}
