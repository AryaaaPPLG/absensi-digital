<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FaceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'face.registered'])->group(function () {
     Route::get('/register-face', [FaceController::class, 'showRegister']);
Route::get('/absensi', [AttendanceController::class, 'showAbsensi']);
Route::post('/absensi/scan-face', [AttendanceController::class, 'scanFace']);
});
Route::get('/verify-digital', [VerificationController::class, 'showVerification'])->name('verify.digital');
Route::post('/verify-face', [VerificationController::class, 'verifyFace']);
Route::post('/verify-qr', [VerificationController::class, 'verifyQr']);
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::middleware(['auth'])->group(function () {
    Route::get('/register-face', [FaceController::class, 'showRegisterFace'])->name('face.register.view');
    Route::post('/register-face', [FaceController::class, 'store'])->name('face.register');
});


Route::post('/face-encoding/save', [FaceEncodingController::class, 'save']);
Route::get('/face-encoding/{user_id}', [FaceEncodingController::class, 'get']);