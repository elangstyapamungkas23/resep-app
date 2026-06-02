<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
{
    $user = auth()->user();

    $totalResep = \App\Models\Resep::where('user_id', $user->id)->count();

    $totalFavorit = \App\Models\Favorit::where('user_id', $user->id)->count();

    $totalRiwayat = \App\Models\Riwayat::where('user_id', $user->id)->count();

    $reseps = \App\Models\Resep::where('user_id', $user->id)->latest()->get();

    return view('profile', compact(
        'user',
        'totalResep',
        'totalFavorit',
        'totalRiwayat',
        'reseps'
    ));
}

    public function edit()
    {
        return view('profile-edit');
    }

    public function update(Request $request)
    {
        $request->validate([
    'name' => 'required',
    'bio' => 'nullable',
    'foto' => 'nullable|image',
    'cover' => 'nullable|image'
]);

        $user = auth()->user();

        $user->name = $request->name;
        $user->bio = $request->bio;

        if ($request->hasFile('foto')) {

            $foto = $request->file('foto')->store('profiles', 'public');

            $user->foto = $foto;
        }

        if ($request->hasFile('cover')) {

            $cover = $request->file('cover')
            ->store('covers', 'public');

            $user->cover = $cover;
        }

        $user->save();

        return redirect('/profile');
    }
}