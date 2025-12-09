<?php

namespace App\Http\Controllers;

use App\Models\Kompetensi;
use Illuminate\Http\Request;

class KompetensiController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'keterampilan');

        // 1 = Kelas Keterampilan & Teknik
        $keterampilan = Kompetensi::where('kelas_keterampilan', 1)
            ->orderBy('nama_kompetensi')
            ->get();

        // 0 = Milenial Job Center
        $mjc = Kompetensi::where('kelas_keterampilan', 0)
            ->orderBy('nama_kompetensi')
            ->get();

        return view('pages.profil.kompetensi-pelatihan', [
            'activeTab'    => $activeTab,
            'keterampilan' => $keterampilan,
            'mjc'          => $mjc,
        ]);
    }
}
