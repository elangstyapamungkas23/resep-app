<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    // 🔥 GET ALL
    public function index()
    {
        $data = Rating::with(['user', 'resep'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // 🔥 GET DETAIL
    public function show($id)
    {
        $data = Rating::with(['user', 'resep'])->find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rating tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // 🔥 STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'resep_id' => 'required',
            'rating' => 'required'
        ]);

        $data = Rating::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Rating berhasil ditambahkan',
            'data' => $data
        ]);
    }

    // 🔥 UPDATE
    public function update(Request $request, $id)
    {
        $data = Rating::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rating tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'rating' => 'required'
        ]);

        $data->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Rating berhasil diupdate',
            'data' => $data
        ]);
    }

    // 🔥 DELETE
    public function destroy($id)
    {
        $data = Rating::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rating tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Rating berhasil dihapus'
        ]);
    }
}