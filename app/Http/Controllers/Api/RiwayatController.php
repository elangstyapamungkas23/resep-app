<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Riwayat;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    // 🔥 GET ALL
    public function index()
    {
        $data = Riwayat::with(['user', 'resep'])
        ->latest()
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // 🔥 GET DETAIL
    public function show($id)
    {
        $data = Riwayat::with(['user', 'resep'])->find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Riwayat tidak ditemukan'
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
        'resep_id' => 'required'
    ]);

    $data = Riwayat::updateOrCreate(
        [
            'user_id' => $validated['user_id'],
            'resep_id' => $validated['resep_id'],
        ],
        [
            'updated_at' => now()
        ]
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Riwayat berhasil ditambahkan',
        'data' => $data
    ]);
}
    // 🔥 UPDATE
    public function update(Request $request, $id)
    {
        $data = Riwayat::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Riwayat tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'user_id' => 'sometimes',
            'resep_id' => 'sometimes'
        ]);

        $data->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat berhasil diupdate',
            'data' => $data
        ]);
    }

    // 🔥 DELETE
    public function destroy($id)
    {
        $data = Riwayat::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Riwayat tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat berhasil dihapus'
        ]);
    }
}