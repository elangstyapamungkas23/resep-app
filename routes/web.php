<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ResepWebController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\AdminController;

Route::get('/', [ResepWebController::class, 'home']);

// AUTH
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerForm']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout']);


// PUBLIC
Route::get('/reseps', [ResepWebController::class, 'index']);
Route::view('/about', 'about');
Route::get('/trending', [ResepWebController::class, 'trending']);

// YANG HARUS LOGIN
Route::middleware('auth')->group(function () {

//profile
    Route::get('/profile', [ProfileController::class, 'index'])
    ->middleware('auth');
    Route::get('/profile/edit', [ProfileController::class, 'edit']);
    Route::post('/profile/edit', [ProfileController::class, 'update']);

    Route::get('/admin', [AdminController::class, 'dashboard'])
    ->middleware('auth');
    Route::delete('/admin/users/{id}',[AdminController::class, 'hapusUser']);


    // rating
    Route::post('/ratings', [ResepWebController::class, 'rating']);
    Route::delete('/ratings/{id}', [ResepWebController::class, 'hapusRating']);
 

    // komentar
    Route::post('/komentars', [ResepWebController::class, 'komentar']);
    Route::put('/komentars/{id}', [ResepWebController::class, 'updateKomentar']);
    Route::delete('/komentars/{id}', [ResepWebController::class, 'hapusKomentar']);
    Route::get('/users/{id}', [ProfileController::class, 'showUser']);

    // favorit
    Route::get('/favorits', [ResepWebController::class, 'favoritIndex']);
    Route::post('/favorit', [ResepWebController::class, 'favorit']);

    // riwayat
    Route::get('/riwayat', [ResepWebController::class, 'riwayat']);
    Route::delete('/riwayat/hapus-semua',[ResepWebController::class, 'hapusSemuaRiwayat']);
    Route::delete('/riwayat/{id}', [ResepWebController::class, 'hapusRiwayat']);

    // tambah resep
    Route::get('/reseps/create', [ResepWebController::class, 'create']);
    Route::post('/reseps/store', [ResepWebController::class, 'store']);
    Route::get('/reseps/{id}/edit',[ResepWebController::class, 'edit']);
    Route::put('/reseps/{id}', [ResepWebController::class, 'update']);
    Route::delete('/reseps/{id}',[ResepWebController::class, 'destroy']);

});

 Route::get('/reseps/{id}', [ResepWebController::class, 'show']);

 Route::get('/gambar/{file}', function ($file) {

    $path = storage_path('app/public/reseps/' . $file);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => '*',
        'Access-Control-Allow-Headers' => '*',
    ]);
});

Route::get('/test-cors', function () {
    return response()->json(
        ['ok' => true],
        200,
        [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => '*',
            'Access-Control-Allow-Headers' => '*',
        ]
    );
});