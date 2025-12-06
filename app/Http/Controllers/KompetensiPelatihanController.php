<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;

class KompetensiPelatihanController extends Controller
{
    public function index(Request $request)
    {
        // Baca tab dari query string, default = 'keterampilan'
        $activeTab = $request->query('tab', 'keterampilan');

        // Ambil data dari model Bidang
        // 1 = Kelas Keterampilan & Teknik
        // 0 = Milenial Job Center
        $keterampilan = Bidang::where('kelas_keterampilan', 1)->get();
        $mjc          = Bidang::where('kelas_keterampilan', 0)->get();

        return view('pages.profil.kompetensi-pelatihan', [
            'activeTab'    => $activeTab,
            'keterampilan' => $keterampilan,
            'mjc'          => $mjc,
        ]);
    }
}
