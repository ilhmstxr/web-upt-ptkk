<?php

namespace App\Services;

use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\Kamar;
use App\Models\PenempatanAsrama;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RoomAssignmentService
{
    /**
     * Assign peserta of a pelatihan to available kamar based on konfigurasi.
     * Rules:
     * - assign by gender groups first (male then female). If asrama full, allow mixing asrama but never mix genders in same kamar.
     * - respects Kamar.total_beds
     */
    public function assignForPelatihan(int $pelatihanId): array
    {
        // Ambil semua pendaftaran aktif untuk pelatihan
        $pendaftaran = PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
            ->with('peserta')
            ->orderBy('id')
            ->get();

        // kelompokkan by gender
        $grouped = $pendaftaran->groupBy(fn($p) => $p->peserta->jenis_kelamin ?? 'Laki-laki');

        // ambil semua kamar yang tersedia (ordered)
        $kamarList = Kamar::where('status', 'Tersedia')->orderBy('asrama_id')->orderBy('nomor_kamar')->get();

        $assignments = [];
        DB::beginTransaction();
        try {
            foreach (['Laki-laki','Perempuan'] as $gender) {
                $listP = $grouped->get($gender, collect());
                if ($listP->isEmpty()) continue;

                $idx = 0;
                foreach ($kamarList as $kamar) {
                    // hitung already occupied di kamar ini (aktif)
                    $occupied = $kamar->penempatans()->penghuniAktif()->count();
                    $capacity = max(0, $kamar->total_beds - $occupied);
                    if ($capacity <= 0) continue;

                    while ($capacity > 0 && $idx < $listP->count()) {
                        /** @var PendaftaranPelatihan $pend */
                        $pend = $listP->get($idx);
                        // buat penempatan
                        PenempatanAsrama::create([
                            'pendaftaran_id' => $pend->id,
                            'kamar_id' => $kamar->id,
                            'checkin_at' => now(),
                        ]);
                        $assignments[] = [
                            'pendaftaran_id' => $pend->id,
                            'peserta_nama' => $pend->peserta->nama,
                            'kamar' => $kamar->nomor_kamar,
                            'asrama' => $kamar->asrama->nama,
                        ];
                        $idx++;
                        $capacity--;
                    }

                    if ($idx >= $listP->count()) break;
                }

                // jika masih sisa peserta (belum ditempatkan) -> coba pakai kamar yang status != 'Tersedia' (campur)
                if ($idx < $listP->count()) {
                    $remaining = $listP->slice($idx);
                    // ambil all kamar yang tidak rusak (termasuk Penuh) dan coba isi bed kosong
                    $otherKamar = Kamar::where('status','!=','Rusak')->orderBy('asrama_id')->get();
                    foreach ($otherKamar as $kamar) {
                        $occupied = $kamar->penempatans()->penghuniAktif()->count();
                        $capacity = max(0, $kamar->total_beds - $occupied);
                        while ($capacity > 0 && $remaining->isNotEmpty()) {
                            $pend = $remaining->shift();
                            PenempatanAsrama::create([
                                'pendaftaran_id' => $pend->id,
                                'kamar_id' => $kamar->id,
                                'checkin_at' => now(),
                            ]);
                            $assignments[] = [
                                'pendaftaran_id' => $pend->id,
                                'peserta_nama' => $pend->peserta->nama,
                                'kamar' => $kamar->nomor_kamar,
                                'asrama' => $kamar->asrama->nama,
                            ];
                            $capacity--;
                        }
                        if ($remaining->isEmpty()) break;
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $assignments;
    }
}
