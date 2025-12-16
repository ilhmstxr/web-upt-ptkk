<?php

namespace App\Http\Controllers;

use App\Models\KontenProgramPelatihan;

class KontenProgramPelatihanController extends Controller
{
    public function index()
{
    $items = KontenProgramPelatihan::orderBy('id')->get();
    return view('pages.profil.program-pelatihan', compact('items'));
}

}
