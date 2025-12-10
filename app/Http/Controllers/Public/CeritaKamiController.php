<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KepalaUpt;

class CeritaKamiController extends Controller
{
    public function index()
    {
        $kepala = KepalaUpt::first();

        return view('pages.profil.cerita-kami', compact('kepala'));
    }

}
