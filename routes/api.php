<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ResepController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\FavoritController;
use App\Http\Controllers\Api\KomentarController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\RiwayatController;

// 🔓 PUBLIC
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 🔐 PROTECTED
Route::middleware('auth:sanctum')->group(function () {

    // PROFILE
    Route::get('/profile', [AuthController::class, 'profile']);

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);

    // RESEPS
    Route::get('/reseps', [ResepController::class, 'index']);
    Route::get('/reseps/{id}', [ResepController::class, 'show']);
    Route::post('/reseps', [ResepController::class, 'store']);
    Route::put('/reseps/{id}', [ResepController::class, 'update']);
    Route::delete('/reseps/{id}', [ResepController::class, 'destroy']);

    // KATEGORIS
    Route::get('/kategoris', [KategoriController::class, 'index']);
    Route::post('/kategoris', [KategoriController::class, 'store']);

    // FAVORITS
    Route::get('/favorits', [FavoritController::class, 'index']);
    Route::post('/favorits', [FavoritController::class, 'store']);
    Route::delete('/favorits/{id}', [FavoritController::class, 'destroy']);

    // KOMENTARS
    Route::get('/komentars', [KomentarController::class, 'index']);
    Route::get('/komentars/{id}', [KomentarController::class, 'show']);
    Route::post('/komentars', [KomentarController::class, 'store']);
    Route::put('/komentars/{id}', [KomentarController::class, 'update']);
    Route::delete('/komentars/{id}', [KomentarController::class, 'destroy']);

    // RATINGS
    Route::get('/ratings', [RatingController::class, 'index']);
    Route::get('/ratings/{id}', [RatingController::class, 'show']);
    Route::post('/ratings', [RatingController::class, 'store']);
    Route::put('/ratings/{id}', [RatingController::class, 'update']);
    Route::delete('/ratings/{id}', [RatingController::class, 'destroy']);

    // RIWAYATS
    Route::get('/riwayats', [RiwayatController::class, 'index']);
    Route::get('/riwayats/{id}', [RiwayatController::class, 'show']);
    Route::post('/riwayats', [RiwayatController::class, 'store']);
    Route::put('/riwayats/{id}', [RiwayatController::class, 'update']);
    Route::delete('/riwayats/{id}', [RiwayatController::class, 'destroy']);
});