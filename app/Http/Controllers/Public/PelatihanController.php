<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    /**
     * Tampilkan daftar semua pelatihan.
     * Metode ini akan dipanggil oleh route('pelatihan.index').
     */
    public function index()
    {
        // Ambil semua data pelatihan (atau yang sudah terpublikasi/terjadwal)
        $pelatihans = Pelatihan::with('instansi')
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(12);

        // Pastikan Anda memiliki view 'pages.pelatihan.index'
        return view('pages.pelatihan.index', compact('pelatihans'));
    }
}