<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\ProfilUpt;

class HomeController extends Controller
{
    public function index()
    {
        // ambil banner aktif, urut berdasarkan sort_order
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // ambil profil upt (ambil satu record terakhir atau pertama)
        $profil = ProfilUpt::orderBy('id', 'desc')->first();

        return view('pages.landing', compact('banners', 'profil'));
    }
}
