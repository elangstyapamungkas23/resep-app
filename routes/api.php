<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ResepController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\FavoritController;
use App\Http\Controllers\Api\KomentarController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\RiwayatController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// AUTH KHUSUS FLUTTER
Route::post('/mobile-register', [MobileAuthController::class, 'register']);
Route::post('/mobile-login', [MobileAuthController::class, 'login']);

// PROFILE FLUTTER
Route::get('/profile/{id}', [ProfileController::class, 'show']);

// RESEP (BISA DIAKSES TANPA LOGIN)
Route::get('/reseps', [ResepController::class, 'index']);
Route::get('/reseps/{id}', [ResepController::class, 'show']);
Route::post('/reseps', [ResepController::class, 'store']);
Route::match(['post', 'put'], '/reseps/{id}', [ResepController::class, 'update']); // <--- SEKARANG BISA TERIMA POST & PUT
Route::delete('/reseps/{id}', [ResepController::class, 'destroy']);

// FAVORIT
Route::get('/favorits', [FavoritController::class, 'index']);
Route::post('/favorits', [FavoritController::class, 'store']);
Route::delete('/favorits/{id}', [FavoritController::class, 'destroy']);

   // RATING
    Route::post('/ratings', [RatingController::class, 'store']);
    Route::delete('/ratings/{id}', [RatingController::class, 'destroy']);

// KATEGORI
Route::get('/kategoris', [KategoriController::class, 'index']);

// KOMENTAR
Route::get('/komentars', [KomentarController::class, 'index']);
Route::get('/komentars/{id}', [KomentarController::class, 'show']);
Route::post('/komentars', [KomentarController::class, 'store']);
Route::put('/komentars/{id}',[KomentarController::class, 'update']);
Route::delete('/komentars/{id}',[KomentarController::class, 'destroy']);

// RATING
Route::get('/ratings', [RatingController::class, 'index']);
Route::get('/ratings/{id}', [RatingController::class, 'show']);

// RIWAYAT
    Route::get('/riwayats', [RiwayatController::class, 'index']);
    Route::get('/riwayats/{id}', [RiwayatController::class, 'show']);
    Route::post('/riwayats', [RiwayatController::class, 'store']);
    Route::put('/riwayats/{id}', [RiwayatController::class, 'update']);
    Route::delete('/riwayats/{id}', [RiwayatController::class, 'destroy']);
/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // PROFILE
    Route::get('/profile', [AuthController::class, 'profile']);
  

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);

    
    // KATEGORI
    Route::post('/kategoris', [KategoriController::class, 'store']);
 
    
});

Route::get('/foto/{id}', [ProfileController::class, 'foto']);

// PROFILE FLUTTER
Route::get('/profile/{id}', [ProfileController::class, 'show']);
Route::post('/profile/update/{id}', [ProfileController::class, 'update']);