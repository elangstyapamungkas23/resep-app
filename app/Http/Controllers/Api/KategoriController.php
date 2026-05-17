<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $data = Kategori::all();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $data = Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}