<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resep;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    public function index(Request $request)
{
    $data = Resep::where('user_id', $request->user()->id)->get();

    return response()->json([
        'status' => 'success',
        'data' => $data
    ]);
}

    public function show($id)
    {
        $data = Resep::with(['user', 'kategori'])->find($id);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required',
        'kategori_id' => 'required',
        'nama_resep' => 'required',
        'deskripsi' => 'required',
        'bahan' => 'required',
        'langkah' => 'required',
    ]);

    $data = Resep::create([
    'user_id' => $request->user()->id,
    'kategori_id' => $request->kategori_id,
    'nama_resep' => $request->nama_resep,
    'deskripsi' => $request->deskripsi,
    'bahan' => $request->bahan,
    'langkah' => $request->langkah
]);
}
public function update(Request $request, $id)
{
    $resep = Resep::find($id);

    if ($resep->user_id != $request->user()->id) {
    return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized'
    ], 403);
}

    $resep->update($request->all());

    return response()->json([
        'status' => 'success',
        'data' => $resep
    ]);
}
public function destroy($id)
{
    $resep = Resep::find($id);

    if (!$resep) {
        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak ditemukan'
        ]);
    }

    $resep->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Data berhasil dihapus'
    ]);
}
}