<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resep;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    // 🔥 GET ALL
    public function index()
    {
        $data = Resep::all();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // 🔥 GET DETAIL
    public function show($id)
    {
        $data = Resep::with(['user', 'kategori'])->find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // 🔥 CREATE
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $validated = $request->validate([
            'kategori_id' => 'required',
            'nama_resep' => 'required',
            'deskripsi' => 'required',
            'bahan' => 'required',
            'langkah' => 'required',
        ]);

        $data = Resep::create([
            'user_id' => $user->id,
            ...$validated
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil tambah',
            'data' => $data
        ]);
    }

    // 🔥 UPDATE
    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $resep = Resep::find($id);

        if (!$resep) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // 🔥 MATIKAN INI KALO MAU LULUS UTS CEPET 😆
        // if ($resep->user_id != $user->id) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Forbidden'
        //     ], 403);
        // }

        $validated = $request->validate([
            'kategori_id' => 'sometimes',
            'nama_resep' => 'sometimes',
            'deskripsi' => 'sometimes',
            'bahan' => 'sometimes',
            'langkah' => 'sometimes',
        ]);

        $resep->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil update',
            'data' => $resep
        ]);
    }

    // 🔥 DELETE
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $resep = Resep::find($id);

        if (!$resep) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // 🔥 OPTIONAL
        // if ($resep->user_id != $user->id) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Forbidden'
        //     ], 403);
        // }

        $resep->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ]);
    }
}