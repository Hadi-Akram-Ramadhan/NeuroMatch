<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EegController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// EEG routes (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/eeg/upload', [EegController::class, 'upload']);
    Route::get('/eeg', [EegController::class, 'index']);
    Route::get('/eeg/latest', [EegController::class, 'latest']);
    Route::get('/eeg/profile', [EegController::class, 'profile']);
});
