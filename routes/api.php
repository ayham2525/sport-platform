<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;


Route::middleware('nfc.token')->post('/attendance', [AttendanceController::class, 'store']);

