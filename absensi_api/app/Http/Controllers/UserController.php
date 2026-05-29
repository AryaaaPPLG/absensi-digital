<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $users = User::with('schoolClass')->orderBy('role')->orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,guru,siswa',
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

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'class_id' => $classId,
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'role' => 'required|in:admin,guru,siswa',
            'rfid_uid' => 'nullable|unique:users,rfid_uid,' . $user->id,
            'kelas' => 'nullable|string|required_with:jurusan',
            'jurusan' => 'nullable|string|required_with:kelas',
        ]);

        $classId = $user->class_id;
        if ($request->filled('kelas') && $request->filled('jurusan')) {
            $schoolClass = SchoolClass::firstOrCreate(
                ['nama_kelas' => $request->kelas, 'jurusan' => $request->jurusan]
            );
            $classId = $schoolClass->id;
        }

        $updateData = $request->only(['name', 'role', 'rfid_uid']);
        $updateData['class_id'] = $classId;

        $user->update($updateData);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
