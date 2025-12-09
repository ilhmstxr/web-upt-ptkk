<?php

namespace App\Http\Controllers;

use App\Models\Kompetensi;  // ⬅️ ganti Bidang jadi Kompetensi
use Illuminate\Http\Request;

class KompetensiController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'keterampilan'); // default tab

        // ambil data dari tabel kompetensi
        $keterampilan = Kompetensi::where('kelas_keterampilan', 1)
            ->orderBy('nama_kompetensi')   // ⬅️ dulu nama_bidang
            ->get();

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
