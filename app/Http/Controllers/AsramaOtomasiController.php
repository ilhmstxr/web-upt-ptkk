<?php

namespace App\Http\Controllers;

use App\Services\AsramaAllocator;
use App\Models\Pelatihan;
use App\Models\Peserta;

class AsramaOtomasiController extends Controller
{
    public function jalankanOtomasi(int $pelatihanId, AsramaAllocator $allocator)
    {
        $pelatihan = Pelatihan::findOrFail($pelatihanId);

        $peserta = Peserta::where('pelatihan_id', $pelatihan->id)
            ->whereDoesntHave('penempatanAsrama')
            ->get();

        $allocator->allocate($pelatihan, $peserta);

        return back()->with('success', 'Otomasi penempatan kamar asrama berhasil dijalankan.');
    }
}
