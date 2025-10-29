<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FaceController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/verify-digital', [VerificationController::class, 'showVerification'])->name('verify.digital');
Route::post('/verify-face', [VerificationController::class, 'verifyFace']);
Route::post('/verify-qr', [VerificationController::class, 'verifyQr']);
Route::get('/register', [AuthController::class, 'registerForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register-face', function () {
    return view('register-face');
});
Route::post('/register-face', [FaceController::class, 'registerFace']);
Route::get('/register-face', [FaceController::class, 'showRegister']);

Route::get('/absensi', [AttendanceController::class, 'showAbsensi']);
Route::post('/absensi/scan-face', [AttendanceController::class, 'scanFace']);
Route::get('/scan', function () {
    return view('scan');
});
