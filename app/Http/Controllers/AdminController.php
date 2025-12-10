<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranPelatihan;
use App\Models\Peserta;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Menampilkan Halaman Manajemen Token Admin
     * (Route yang memanggil metode ini harus dilindungi oleh middleware('auth') standar Laravel)
     */
    public function showTokenManagement()
    {
        // 1. Ambil data pendaftaran yang belum memiliki token (pending)
        $pendingPendaftarans = PendaftaranPelatihan::with('peserta', 'pelatihan')
            ->whereNull('assessment_token')
            ->get();

        // 2. Hitung Statistik Token
        $totalPendaftaran = PendaftaranPelatihan::count();
        $tokensGenerated = PendaftaranPelatihan::whereNotNull('assessment_token')->count();
        $tokensPending = $pendingPendaftarans->count(); // Menggunakan hasil query di atas

        // 3. Mengirimkan data ke view admin.token_management
        return view('admin.token_management', compact(
            'pendingPendaftarans',
            'totalPendaftaran',
            'tokensGenerated',
            'tokensPending'
        ));
    }
    
    /*
    |--------------------------------------------------------------------------
    | Anda dapat menambahkan metode admin lainnya di bawah ini, contoh:
    |--------------------------------------------------------------------------
    */
    
    // public function index() 
    // {
    //     // Menampilkan dashboard utama admin
    //     return view('admin.index');
    // }

    // public function manageUsers() 
    // {
    //     // Menampilkan halaman manajemen pengguna
    //     // ...
    // }
}