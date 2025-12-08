<?php

namespace App\Http\Controllers;

use App\Services\AsramaAllocator;
use App\Models\Pelatihan;
use App\Models\Peserta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class AsramaOtomasiController extends Controller
{
    /**
     * Jalankan otomasi penempatan kamar asrama untuk suatu pelatihan.
     *
     * Mengambil peserta lewat relasi pendaftaranPelatihans dan mengecek
     * penempatan berdasarkan penempatanAsramas (filter pelatihan_id).
     */
    public function jalankanOtomasi(int $pelatihanId, AsramaAllocator $allocator): RedirectResponse
    {
        $pelatihan = Pelatihan::findOrFail($pelatihanId);

        // Ambil peserta yang punya pendaftaran untuk pelatihan ini (opsional: diterima)
        // dan yang BELUM punya penempatan untuk pelatihan ini.
        $peserta = Peserta::whereHas('pendaftaranPelatihans', function ($q) use ($pelatihanId) {
                $q->where('pelatihan_id', $pelatihanId);

                // optional: hanya yg diterima (safe-check kalau kolom ada)
                if (Schema::hasColumn('pendaftaran_pelatihan', 'status_pendaftaran')) {
                    $q->where('status_pendaftaran', 'diterima');
                }
            })
            ->whereDoesntHave('penempatanAsramas', function ($q) use ($pelatihanId) {
                $q->where('pelatihan_id', $pelatihanId);
            })
            ->get();

        if ($peserta->isEmpty()) {
            return back()->with('warning', 'Tidak ada peserta yang perlu ditempatkan untuk pelatihan ini.');
        }

        // Jalankan allocator (AsramaAllocator akan handle logika detail)
        $allocator->allocate($pelatihan, $peserta);

        return back()->with('success', 'Otomasi penempatan kamar asrama berhasil dijalankan.');
    }
}
