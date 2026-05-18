<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingWebController extends Controller
{
    public function rating(Request $request)
{
    $request->validate([
        'resep_id' => 'required',
        'rating' => 'required|integer|min:1|max:5'
    ]);

    Rating::updateOrCreate(
        [
            'user_id' => 1,
            'resep_id' => $request->resep_id
        ],
        [
            'rating' => $request->rating
        ]
    );

    return back();
}
}