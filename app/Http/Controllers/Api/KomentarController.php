<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use Illuminate\Http\Request;

class KomentarController extends Controller
{
    // 🔥 GET ALL
    public function index()
    {
        $data = Komentar::with(['user', 'resep'])->get();

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
            'komentar' => 'required'
        ]);

        $data = Komentar::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Komentar berhasil ditambahkan',
            'data' => $data
        ]);
    }

    // 🔥 DETAIL
    public function show($id)
    {
        $data = Komentar::with(['user', 'resep'])->find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Komentar tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // 🔥 DELETE
    public function destroy($id)
    {
        $data = Komentar::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Komentar tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Komentar berhasil dihapus'
        ]);
    }
}