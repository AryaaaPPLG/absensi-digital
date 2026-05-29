<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\AttendanceController;

Route::post('/attendance/scan', [RfidController::class, 'scan']);
Route::get('/attendance/realtime', [AttendanceController::class, 'getRealtimeStats']);