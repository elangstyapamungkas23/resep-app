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
use Illuminate\Support\Facades\DB;

class ResepWebController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;
    $kategori = $request->kategori;

    $kategoris = Kategori::all();

    $reseps = Resep::with(['ratings','user'])

        ->when($search, function ($query) use ($search) {

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

public function home()
{
    $trending = Resep::select(
            'reseps.*',
            DB::raw('AVG(ratings.rating) as rata_rating'),
            DB::raw('COUNT(ratings.id) as total_rating')
        )
        ->leftJoin('ratings', 'reseps.id', '=', 'ratings.resep_id')
        ->groupBy(
            'reseps.id',
            'reseps.user_id',
            'reseps.kategori_id',
            'reseps.nama_resep',
            'reseps.deskripsi',
            'reseps.bahan',
            'reseps.langkah',
            'reseps.gambar',
            'reseps.created_at',
            'reseps.updated_at'
        )
        ->havingRaw('COUNT(ratings.id) >= 1')
        ->orderByDesc('rata_rating')
        ->orderByDesc('total_rating')
        ->take(3)
        ->get();

    return view('home', compact('trending'));
}

    public function show($id)
    {
        $resep = Resep::with([
            'ratings',
            'komentars.user',
            'favorits'
        ])->findOrFail($id);

        if (auth()->check()) {

            Riwayat::create([
                'user_id' => auth()->id(),
                'resep_id' => $resep->id
            ]);
        }

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
                'user_id' => auth()->id(),
                'resep_id' => $request->resep_id
            ],
            [
                'rating' => $request->rating
            ]
        );

        return back();
    }

    public function hapusRating($id)
    {
        $rating = Rating::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $rating->delete();

        return back();
    }

    public function komentar(Request $request)
    {
        $request->validate([
            'resep_id' => 'required',
            'isi' => 'required'
        ]);

        Komentar::create([
            'user_id' => auth()->id(),
            'resep_id' => $request->resep_id,
            'komentar' => $request->isi
        ]);

        return back();
    }

    public function updateKomentar(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required'
        ]);

        $komentar = Komentar::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $komentar->update([
            'komentar' => $request->komentar
        ]);

        return back();
    }

    public function hapusKomentar($id)
{
    $komentar = Komentar::findOrFail($id);

    if (
        auth()->id() != $komentar->user_id
        && auth()->user()->role != 'admin'
    ) {
        abort(403);
    }

    $komentar->delete();

    return back();
}

    public function favorit(Request $request)
    {
        $favorit = Favorit::where('user_id', auth()->id())
            ->where('resep_id', $request->resep_id)
            ->first();

        if ($favorit) {

            $favorit->delete();

        } else {

            Favorit::create([
                'user_id' => auth()->id(),
                'resep_id' => $request->resep_id
            ]);
        }

        return back();
    }

    public function favoritIndex()
    {
        $favorits = Favorit::with('resep')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('favorits', compact('favorits'));
    }

    public function riwayat()
    {
        $riwayats = Riwayat::with('resep')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('riwayat', compact('riwayats'));
    }
    
    public function hapusRiwayat($id)
{
    $riwayat = Riwayat::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $riwayat->delete();

    return back();
}

public function hapusSemuaRiwayat()
{
    Riwayat::where(
        'user_id',
        auth()->id()
    )->delete();

    return back();
}

public function create()
{
    $kategoris = Kategori::all();

    return view('reseps.create', compact('kategoris'));
}

public function store(Request $request)
{
    $request->validate([
        'nama_resep' => 'required',
        'kategori_id' => 'required',
        'deskripsi' => 'required',
        'bahan' => 'required',
        'langkah' => 'required',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $gambar = null;

    if ($request->hasFile('gambar')) {

        $gambar = $request
            ->file('gambar')
            ->store('reseps', 'public');

    }

    Resep::create([
        'user_id' => auth()->id(),
        'kategori_id' => $request->kategori_id,
        'nama_resep' => $request->nama_resep,
        'deskripsi' => $request->deskripsi,
        'bahan' => $request->bahan,
        'langkah' => $request->langkah,
        'gambar' => $gambar
    ]);

    return redirect('/reseps')
        ->with('success', 'Resep berhasil ditambahkan');
}

public function edit($id)
{
    $resep = Resep::findOrFail($id);

    if(
    auth()->id() != $resep->user_id &&
    auth()->user()->role != 'admin'
){
    abort(403);
}

    $kategoris = Kategori::all();

    return view('reseps.edit', compact(
        'resep',
        'kategoris'
    ));
}

public function update(Request $request, $id)
{
    $resep = Resep::findOrFail($id);

    if ($resep->user_id != auth()->id()) {
        abort(403);
    }

    $request->validate([
        'nama_resep' => 'required',
        'kategori_id' => 'required',
        'deskripsi' => 'required',
        'bahan' => 'required',
        'langkah' => 'required'
    ]);

    $gambar = $resep->gambar;

    if ($request->hasFile('gambar')) {

        $gambar = $request
            ->file('gambar')
            ->store('reseps', 'public');

    }

    $resep->update([
        'nama_resep' => $request->nama_resep,
        'kategori_id' => $request->kategori_id,
        'deskripsi' => $request->deskripsi,
        'bahan' => $request->bahan,
        'langkah' => $request->langkah,
        'gambar' => $gambar
    ]);

    return redirect('/reseps')
        ->with('success', 'Resep berhasil diupdate');
}

public function destroy($id)
{
    $resep = Resep::findOrFail($id);

    if (
        auth()->user()->role !== 'admin' &&
        $resep->user_id !== auth()->id()
    ) {
        abort(403);
    }

    $resep->delete();

    return redirect('/reseps')
        ->with('success', 'Resep berhasil dihapus');
}

public function trending()
{

    if (!auth()->check()) {
        return redirect('/login');
    }

    // Ambil data resep top 30
    $reseps = Resep::with(['ratings', 'user'])
        ->withAvg('ratings', 'rating')
        ->withCount('ratings')
        ->whereHas('ratings') 
        ->orderByDesc('ratings_avg_rating')
        ->orderByDesc('ratings_count')
        ->take(30)
        ->get();

    return view('trending', compact('reseps'));
}


}