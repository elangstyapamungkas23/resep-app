<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Resep;
use App\Models\Rating;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\Favorit;
use App\Models\Riwayat;
use Illuminate\Http\Request;

class ResepWebController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;
    $kategori = $request->kategori;

    $kategoris = Kategori::all();

    $reseps = Resep::when($search, function ($query) use ($search) {

            $query->where('nama_resep', 'like', '%' . $search . '%');

        })

        ->when($kategori, function ($query) use ($kategori) {

            $query->where('kategori_id', $kategori);

        })

        ->latest()
        ->get();

    return view('reseps.index', compact(
        'reseps',
        'search',
        'kategoris',
        'kategori'
    ));
}

    public function show($id)
{
    $resep = Resep::with([
        'ratings',
        'komentars.user',
        'favorits'
    ])->findOrFail($id);

    \App\Models\Riwayat::create([
        'user_id' => 1,
        'resep_id' => $resep->id
    ]);

    return view('detail', compact('resep'));
}

    public function rating(Request $request)
    {
        $request->validate([
            'resep_id' => 'required',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        Rating::updateOrCreate(
            [
                'user_id' => 1,
                'resep_id' => $request->resep_id
            ],
            [
                'rating' => $request->rating
            ]
        );

        return redirect()->back();
    }

public function hapusRating($id)
{
    $rating = \App\Models\Rating::findOrFail($id);

    $rating->delete();

    return back();
}

    public function komentar(Request $request)
{
    $request->validate([
        'resep_id' => 'required',
        'isi' => 'required'
    ]);

    \App\Models\Komentar::create([
        'user_id' => 1,
        'resep_id' => $request->resep_id,
        'komentar' => $request->isi
    ]);

    return back();
}

public function favoritIndex()
{
    $favorits = Favorit::with('resep')
        ->where('user_id', 1)
        ->latest()
        ->get();

    return view('favorits', compact('favorits'));
}

public function favorit(Request $request)
{
    $favorit = Favorit::where('user_id', 1)
        ->where('resep_id', $request->resep_id)
        ->first();

    if ($favorit) {

        $favorit->delete();

    } else {

        Favorit::create([
            'user_id' => 1,
            'resep_id' => $request->resep_id
        ]);
    }

    return back();
}

public function updateKomentar(Request $request, $id)
{
    $request->validate([
        'komentar' => 'required'
    ]);

    $komentar = \App\Models\Komentar::findOrFail($id);

    $komentar->update([
        'komentar' => $request->komentar
    ]);

    return back();
}


public function hapusKomentar($id)
{
    $komentar = \App\Models\Komentar::findOrFail($id);

    $komentar->delete();

    return back();
}

public function riwayat()
{
    $riwayats = \App\Models\Riwayat::with('resep')
        ->latest()
        ->get();

    return view('riwayat', compact('riwayats'));
}

}