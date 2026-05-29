<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('register');
    }

   

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:guru,siswa'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        
        // Buat throttle key unik berdasarkan username dan IP
        $throttleKey = Str::transliterate(Str::lower($request->input('username')).'|'.$request->ip());

        // Cek apakah sudah melebihi batas percobaan (3 kali)
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            return back()->withErrors([
                'loginError' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$minutes} menit."
            ]);
        }

        if (Auth::attempt($credentials)) {
            // Jika login berhasil, hapus catatan percobaan salah
            RateLimiter::clear($throttleKey);
            
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Login Berhasil');
        }

        // Jika login gagal, tambahkan hit ke rate limiter dengan waktu decay 180 menit (10800 detik)
        RateLimiter::hit($throttleKey, 10800);

        return back()->withErrors(['loginError' => 'Username atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
