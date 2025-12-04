<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SorotanPelatihan;

class LandingController extends Controller
{
    public function index()
    {
        $sorotan = SorotanPelatihan::with('fotos')
            ->where('is_published', true)
            ->latest()
            ->get();

        return view('pages.landing', compact('sorotan'));
    }

}
