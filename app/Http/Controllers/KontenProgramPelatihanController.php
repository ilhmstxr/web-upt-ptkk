<?php

namespace App\Http\Controllers;

use App\Models\KontenProgramPelatihan;

class KontenProgramPelatihanController extends Controller
{
    public function index()
    {
        // Sesuaikan ID dengan yang di phpMyAdmin kamu
        $mtu      = KontenProgramPelatihan::find(1); // Mobil Training Unit
        $program  = KontenProgramPelatihan::find(2); // Diklat Peningkatan Kompetensi
        $sertifikasi = KontenProgramPelatihan::find(3); // boleh belum ada, nanti null

        return view('pages.profil.program-pelatihan', compact('mtu', 'program', 'sertifikasi'));
    }
}
