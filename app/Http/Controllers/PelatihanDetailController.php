<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanDetailController extends Controller
{
    public function show($slug)
    {
        $pelatihan = Pelatihan::where('slug', $slug)
            ->with([
                'bidangPelatihan.instruktur',
                'bidangPelatihan.bidang',
                'pendaftaranPelatihan.peserta',
                'instansi'
            ])
            ->firstOrFail();

        return view('detail-pelatihan', compact('pelatihan'));
    }
}
