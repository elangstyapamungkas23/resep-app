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
        $data = Resep::with(['user', 'kategori'])->get();

        $data->transform(function ($item) {

            if ($item->gambar) {
                $item->gambar = asset('storage/' . $item->gambar);
            }

            return $item;
        });

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

        if ($data->gambar) {
            $data->gambar = asset('storage/' . $data->gambar);
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
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $gambarPath = null;

        if ($request->hasFile('gambar')) {

            $gambarPath = $request->file('gambar')->store('reseps', 'public');
        }

        $data = Resep::create([
            'user_id' => $user->id,
            'kategori_id' => $validated['kategori_id'],
            'nama_resep' => $validated['nama_resep'],
            'deskripsi' => $validated['deskripsi'],
            'bahan' => $validated['bahan'],
            'langkah' => $validated['langkah'],
            'gambar' => $gambarPath
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Resep berhasil ditambahkan',
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

        $validated = $request->validate([
            'kategori_id' => 'sometimes',
            'nama_resep' => 'sometimes',
            'deskripsi' => 'sometimes',
            'bahan' => 'sometimes',
            'langkah' => 'sometimes',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // 🔥 UPDATE GAMBAR
        if ($request->hasFile('gambar')) {

            $gambarPath = $request->file('gambar')->store('reseps', 'public');

            $validated['gambar'] = $gambarPath;
        }

        $resep->update($validated);

        if ($resep->gambar) {
            $resep->gambar = asset('storage/' . $resep->gambar);
        }

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

        $resep->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ]);
    }
}