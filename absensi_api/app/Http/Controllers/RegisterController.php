<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
            'kelas' => 'nullable|string',
            'jurusan' => 'nullable|string',
        ]);
       // sementara untuk debugging
        // logger($request->all());
        if (!$request->has('username')) {
    return back()->withErrors(['username' => 'Field username tidak terkirim.']);
}




        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
        ]);

        Auth::login($user);

        return redirect('/register-rfid');
    }
}
