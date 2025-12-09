<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PendaftaranPelatihan;
use App\Models\Peserta;

class EnsureActiveTrainingSession
{
    public function handle(Request $request, Closure $next)
    {
        // kalau sudah lengkap, lanjut
        if (
            session('pendaftaran_pelatihan_id') &&
            session('pelatihan_id') &&
            session('kompetensi_id')
        ) {
            return $next($request);
        }

        // ambil peserta dari session login assessment
        $pesertaId = session('peserta_id');

        if ($pesertaId) {
            $peserta = Peserta::find($pesertaId);

            if ($peserta) {
                $pendaftaran = PendaftaranPelatihan::query()
                    ->where('peserta_id', $peserta->id)
                    ->latest('tanggal_pendaftaran')
                    ->first();

                if ($pendaftaran) {
                    session([
                        'pendaftaran_pelatihan_id' => $pendaftaran->id,
                        'pelatihan_id'            => $pendaftaran->pelatihan_id,
                        'kompetensi_id'           => $pendaftaran->kompetensi_id ?? null,
                    ]);
                }
            }
        }

        return $next($request);
    }
}
