<?php

namespace App\Http\Controllers;

use App\Models\KontenProgramPelatihan;

use Illuminate\Http\Request;

class KontenProgramPelatihanController extends Controller
{
    // Halaman utama program pelatihan
    public function index()
    {
        // Ambil data spesifik berdasarkan judul
        $program = KontenProgramPelatihan::where('judul', 'Diklat Peningkatan Kompetensi')->first();
        $sertifikasi = KontenProgramPelatihan::where('judul', 'Sertifikasi Berbasis KKNI Bertaraf Nasional')->first();
        $mtu = KontenProgramPelatihan::where('judul', 'Mobil Training Unit')->first();

        // Kirim ke view
        return view('pages.profil.program-pelatihan', compact('program', 'sertifikasi', 'mtu'));
    }

    // Kalau mau detail per program (opsional)
    public function show($id)
    {
        $program = KontenProgramPelatihan::findOrFail($id);

        return view('pages.pelatihan.show', compact('program'));
    }

}
