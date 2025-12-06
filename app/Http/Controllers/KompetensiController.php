<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;

class KompetensiController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'keterampilan'); // default tab

        // ambil data dari tabel bidang
        $keterampilan = Bidang::where('kelas_keterampilan', 1)
            ->orderBy('nama_bidang')
            ->get();

        $mjc = Bidang::where('kelas_keterampilan', 0)
            ->orderBy('nama_bidang')
            ->get();

      return view('pages.profil.kompetensi-pelatihan', [
    'activeTab'    => $activeTab,
    'keterampilan' => $keterampilan,
    'mjc'          => $mjc,
]);

    }
}
