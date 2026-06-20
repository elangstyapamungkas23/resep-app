<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use Illuminate\Http\Request;

class FavoritController extends Controller
{
    public function index()
{
    $data = Favorit::with('resep')->get();

    return response()->json([
        'status' => 'success',
        'data' => $data
    ]);
}

    public function store(Request $request)
{
    $data = Favorit::firstOrCreate([
        'user_id' => $request->user_id,
        'resep_id' => $request->resep_id
    ]);

    return response()->json([
        'status' => 'success',
        'data' => $data
    ]);
}

    public function destroy($id)
    {
        $data = Favorit::find($id);

        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Favorit dihapus'
        ]);
    }
}