<?php

namespace App\Traits;

use App\Models\Bidang;
use App\Models\PendaftaranPelatihan;
use Illuminate\Support\Facades\DB;

trait ManagesRegistrationTokens
{
    /**
     * Generate nomor registrasi yang unik untuk kombinasi pelatihan dan bidang.
     * Menggunakan pessimistic lock untuk mencegah race condition.
     */
    private function generateToken(int $pelatihanId, int $bidangId): array
    {
        return DB::transaction(function () use ($pelatihanId, $bidangId) {
            $bidang     = Bidang::findOrFail($bidangId);
            $kodeBidang = $bidang->kode ?? $this->akronim($bidang->nama);

            // Kunci baris yang relevan untuk dihitung agar akurat
            PendaftaranPelatihan::join('peserta', 'pendaftaran_pelatihan.peserta_id', '=', 'peserta.id')
                ->where('pendaftaran_pelatihan.pelatihan_id', $pelatihanId)
                ->where('peserta.bidang_id', $bidangId)
                ->select('pendaftaran_pelatihan.id')
                ->lockForUpdate()
                ->get();

            // Hitung jumlah setelah di-lock
            $jumlah = PendaftaranPelatihan::join('peserta', 'pendaftaran_pelatihan.peserta_id', '=', 'peserta.id')
                ->where('pendaftaran_pelatihan.pelatihan_id', $pelatihanId)
                ->where('peserta.bidang_id', $bidangId)
                ->count();

            $nextUrut = $jumlah + 1;
            $nomor    = sprintf('%d-%s-%03d', $pelatihanId, strtoupper($kodeBidang), $nextUrut);

            // Asumsi tabel pendaftaran_pelatihan punya kolom 'urutan_per_bidang'
            return ['nomor' => $nomor, 'urutan' => $nextUrut];
        }, 3);
    }

    private function akronim(string $nama): string
    {
        $words = preg_split('/\s+/', trim($nama));
        $akronim = collect($words)->map(fn($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('');
        $akronim = preg_replace('/[^A-Za-z0-9]/', '', $akronim) ?: 'BDG';
        return strtoupper($akronim);
    }
}