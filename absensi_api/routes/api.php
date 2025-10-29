<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaceController;
use App\Http\Controllers\AttendanceController;



Route::post('/attendance/qr', [AttendanceController::class, 'qrAttendance']);
Route::post('/attendance/face', [AttendanceController::class, 'faceAttendance']);
