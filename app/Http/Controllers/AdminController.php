<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resep;
use App\Models\Komentar;
use App\Models\Rating;

class AdminController extends Controller
{
    public function dashboard()
    {
        if(auth()->user()->role != 'admin'){
            abort(403);
        }

        $totalUser = User::count();
        $totalResep = Resep::count();
        $totalKomentar = Komentar::count();
        $totalRating = Rating::count();

        // ✅ SEKARANG NGE-LOS: ->take(5) dibuang biar semua resep terbaca di scroll-box
        $reseps = Resep::with('user')
            ->latest()
            ->get();

        // ✅ SEKARANG NGE-LOS: ->take(5) dibuang biar semua komentar terbaca di scroll-box
        $komentars = Komentar::with(['user','resep'])
            ->latest()
            ->get();

        $users = User::latest()->get();

        return view('admin.dashboard', compact(
            'totalUser',
            'totalResep',
            'totalKomentar',
            'totalRating',
            'reseps',
            'komentars',
            'users',
        ));
    }

    public function hapusUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == auth()->id()) {
            return back();
        }

        $user->delete();

        return back()->with(
            'success',
            'User berhasil dihapus'
        );
    }
}