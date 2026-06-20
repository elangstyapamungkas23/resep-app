<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resep;
use Illuminate\Http\Request;
use App\Models\Rating;
use Illuminate\Support\Facades\Storage; // 🔥 1. PASTIKAN INI DI-IMPORT UNTUK MENGHAPUS GAMBAR

class ResepController extends Controller
{
    // 🔥 GET ALL
    public function index(Request $request)
    {
        $query = Resep::with('user')
            ->withAvg('ratings', 'rating')
            ->withCount('ratings');

        // FILTER KATEGORI
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $reseps = $query
            ->orderByDesc('ratings_avg_rating')
            ->get();

        foreach ($reseps as $item) {
            $item->gambar =
                "http://192.168.18.55:8000/gambar/" .
                basename($item->gambar);
        }

        return response()->json([
            'status' => 'success',
            'data' => $reseps
        ]);
    }

    // 🔥 GET DETAIL
    public function show($id)
    {
        $data = Resep::with([
            'user',
            'kategori',
            'komentars.user'
        ])
        ->withAvg('ratings', 'rating')
        ->withCount('ratings')
        ->find($id);

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
    // 🔥 CREATE (VERSI BEBAS TOKEN / PUBLIK)
public function store(Request $request)
{
    // Hapus baris $request->user() yang memicu 401
    $validated = $request->validate([
        'user_id' => 'required', // Bikin user_id jadi required di validasi
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
        'user_id' => $request->user_id, // Ambil langsung dari field parameter Flutter
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

    // 🔥 UPDATE (SUDAH DIPERBAIKI SINTAKS DAN LOGIKANYA)
    public function update(Request $request, $id)
    {
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

        if ($request->hasFile('gambar')) {
            // Hapus file gambar lama dari server jika user mengunggah foto baru
            if ($resep->gambar && Storage::disk('public')->exists($resep->gambar)) {
                Storage::disk('public')->delete($resep->gambar);
            }

            // Simpan gambar baru
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
    public function destroy($id)
    {
        $resep = Resep::find($id);

        if (!$resep) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Opsional: Hapus berkas gambar dari storage saat resep dihapus
        if ($resep->gambar && Storage::disk('public')->exists($resep->gambar)) {
            Storage::disk('public')->delete($resep->gambar);
        }

        $resep->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ]);
    }
}