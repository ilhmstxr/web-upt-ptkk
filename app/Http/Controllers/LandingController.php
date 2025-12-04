<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\ProfilUPT;
use Illuminate\Support\Facades\Cache;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil banner aktif, cache 30 menit
        $banners = Cache::remember('landing_banners', 1800, function () {
            return Banner::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->limit(6)
                ->get();
        });

        // Ambil berita terbaru
        $beritas = Cache::remember('landing_beritas', 1800, function () {
            return Berita::where('is_published', true)
                ->orderBy('published_at', 'desc')
                ->limit(6)
                ->get();
        });

        // Ambil profil UPT (1 baris)
        $profil = Cache::remember('landing_profil', 1800, function () {
            return ProfilUPT::first();
        });

        return view('pages.landing', compact('banners', 'beritas', 'profil'));
    }
}
