<?php

namespace App\Http\Controllers;

use App\Models\Kompetensi;
use Illuminate\Http\Request;

class KompetensiController extends Controller
{
    public function index(Request $request)
    {
        // ambil semua kompetensi urut berdasarkan id (paling kecil tampil dulu)
        $listKompetensi = Kompetensi::orderBy('id', 'asc')->get();

        return view('pages.profil.kompetensi-pelatihan', [
            'listKompetensi' => $listKompetensi,
        ]);
    }
}
