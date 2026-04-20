<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResepController;
use App\Http\Controllers\Api\AuthController;

// 🔓 PUBLIC
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 🔐 PROTECTED
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/reseps', [ResepController::class, 'index']);
    Route::get('/reseps/{id}', [ResepController::class, 'show']);
    Route::post('/reseps', [ResepController::class, 'store']);
    Route::put('/reseps/{id}', [ResepController::class, 'update']);
    Route::delete('/reseps/{id}', [ResepController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']); // ✅ cukup 1
});