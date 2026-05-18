<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ResepWebController;
use App\Http\Controllers\Web\RatingWebController;

Route::get('/', function () {
    return view('home');
});

Route::get('/reseps', [ResepWebController::class, 'index']);
Route::get('/reseps/{id}', [ResepWebController::class, 'show']);

Route::view('/about', 'about');

//Ratings
Route::post('/ratings', [ResepWebController::class, 'rating']);
Route::delete('/ratings/{id}', [ResepWebController::class, 'hapusRating']);
//Komentars
Route::post('/komentars', [ResepWebController::class, 'komentar']);
Route::put('/komentars/{id}', [ResepWebController::class, 'updateKomentar']);
Route::delete('/komentars/{id}', [ResepWebController::class, 'hapusKomentar']);
//Favorits
Route::get('/favorits', [ResepWebController::class, 'favoritIndex']);
Route::post('/favorit', [ResepWebController::class, 'favorit']);
//Riwayat
Route::get('/riwayat', [ResepWebController::class, 'riwayat']);