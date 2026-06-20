<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resep;
use App\Models\Favorit;
use App\Models\Riwayat;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'bio' => $user->bio,
                'foto' => $user->foto
                    ? asset('storage/' . $user->foto)
                    : null,

                'cover' => $user->cover
                    ? asset('storage/' . $user->cover)
                    : null,
            ],

            'total_resep' => Resep::where('user_id',$user->id)->count(),

            'total_favorit' => Favorit::where('user_id',$user->id)->count(),

            'total_riwayat' => Riwayat::where('user_id',$user->id)->count(),

            'reseps' => Resep::where('user_id',$user->id)->latest()->get()
        ]);
    }
    public function foto($id)
{
    $user = User::findOrFail($id);

    return response()->file(
        storage_path('app/public/' . $user->foto),
        [
            'Access-Control-Allow-Origin' => '*'
        ]
    );
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $user->name = $request->name;
    $user->bio = $request->bio;

    if ($request->hasFile('foto')) {

        $foto = $request->file('foto')->store('profiles', 'public');

        $user->foto = $foto;
    }

    if ($request->hasFile('cover')) {

        $cover = $request->file('cover')->store('covers', 'public');

        $user->cover = $cover;
    }

    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Profil berhasil diupdate'
    ]);
}
}