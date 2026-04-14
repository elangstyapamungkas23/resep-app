<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResepController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 🔥 TAMBAHAN KITA
Route::get('/reseps', [ResepController::class, 'index']);
Route::get('/reseps/{id}', [ResepController::class, 'show']);
Route::post('/reseps', [ResepController::class, 'store']);
Route::put('/reseps/{id}', [ResepController::class, 'update']);
Route::delete('/reseps/{id}', [ResepController::class, 'destroy']);