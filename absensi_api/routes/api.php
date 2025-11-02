<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controller\Api\FaceEncodingController;



Route::post('/attendance/qr', [AttendanceController::class, 'qrAttendance']);
Route::post('/attendance/face', [AttendanceController::class, 'faceAttendance']);

Route::post('/face-encoding/save', [FaceEncodingController::class, 'save']);
Route::get('/face-encoding/{user_id}', [FaceEncodingController::class, 'get']);

Route::post('/attendance/record', [AttendanceApiController::class, 'record']);