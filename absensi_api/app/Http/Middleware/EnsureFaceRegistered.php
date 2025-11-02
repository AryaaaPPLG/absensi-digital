<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FaceEncoding;

class EnsureFaceRegistered
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Cek apakah user sudah punya data face encoding
        $hasFace = FaceEncoding::where('user_id', $user->id)->exists();

        if (!$hasFace) {
            return redirect('/register-face')->with('error', 'Harap daftarkan wajah terlebih dahulu sebelum absensi.');
        }

        return $next($request);
    }
}
