<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // RFID Registration
    Route::get('/register-rfid', [RfidController::class, 'showRegistrationForm'])->name('rfid.register.view');
    Route::post('/register-rfid', [RfidController::class, 'register'])->name('rfid.register');

    // Rekap Absensi
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::post('/rekap/update', [RekapController::class, 'update'])->name('rekap.update');
    Route::post('/rekap/bulk-hadir', [RekapController::class, 'bulkHadir'])->name('rekap.bulk-hadir');
    Route::get('/rekap/export', [RekapController::class, 'export'])->name('rekap.export');

    // User Management (Admin Only)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/absensi', [AttendanceController::class, 'showAbsensi'])->name('absensi.view');
});

Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
