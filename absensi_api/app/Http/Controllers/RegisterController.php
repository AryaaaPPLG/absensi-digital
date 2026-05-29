<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:guru,siswa',
            'kelas' => 'nullable|string|required_with:jurusan',
            'jurusan' => 'nullable|string|required_with:kelas',
        ]);

        $classId = null;
        if ($request->filled('kelas') && $request->filled('jurusan')) {
            $schoolClass = SchoolClass::firstOrCreate(
                ['nama_kelas' => $request->kelas, 'jurusan' => $request->jurusan]
            );
            $classId = $schoolClass->id;
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'class_id' => $classId,
        ]);

        Auth::login($user);

        return redirect('/register-rfid');
    }
}
