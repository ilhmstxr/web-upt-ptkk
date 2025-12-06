<?php

namespace App\Traits; // <-- PASTIKAN NAMESPACE INI BENAR

use App\Models\Kompetensi;
use App\Models\PendaftaranPelatihan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait ManagesRegistrationTokens // <-- PASTIKAN NAMA TRAIT INI BENAR
{
    /**
     * Generate nomor registrasi yang unik untuk kombinasi pelatihan dan kompetensi.
     * Menggunakan pessimistic lock untuk mencegah race condition.
     */
    private function generateToken(int $pelatihanId, int $kompetensiId): array
    {
        return DB::transaction(function () use ($pelatihanId, $kompetensiId) {
            $kompetensi     = \App\Models\Kompetensi::findOrFail($kompetensiId);
            $kodeKompetensi = $kompetensi->kode ?? $this->akronim($kompetensi->nama_kompetensi);

            // Kunci baris yang relevan untuk dihitung agar akurat
            // Kita lock berdasarkan pelatihan dan kompetensi
            PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('kompetensi_id', $kompetensiId)
                ->select('id')
                ->lockForUpdate()
                ->get();

            // Hitung max urutan untuk (pelatihan, kompetensi)
            $maxUrutan = PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('kompetensi_id', $kompetensiId)
                ->max('urutan_per_kompetensi');

            $nextUrut = ($maxUrutan ?? 0) + 1;
            // Format: ID_PELATIHAN - KODE_KOMPETENSI - NO_URUT (001)
            $nomor    = sprintf('%d-%s-%03d', $pelatihanId, strtoupper($kodeKompetensi), $nextUrut);

            return ['nomor' => $nomor, 'urutan' => $nextUrut];
        }, 3);
    }

    private function akronim(string $nama): string
    {
        $words = preg_split('/\s+/', trim($nama));
        $akronim = collect($words)->map(fn($w) => Str::substr($w, 0, 1))->implode('');
        $akronim = preg_replace('/[^A-Za-z0-9]/', '', $akronim) ?: 'KMP';
        return strtoupper($akronim);
    }
}
