<?php
// app/Services/AsramaAllocator.php

namespace App\Services;

use App\Models\Asrama;
use App\Models\Kamar;
use App\Models\Peserta;
use App\Models\Pelatihan;
use App\Models\PenempatanAsrama;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AsramaAllocator
{
    /**
     * @param  \App\Models\Pelatihan  $pelatihan   Pelatihan yang sedang berjalan / dibuka
     * @param  \Illuminate\Support\Collection<int,\App\Models\Peserta>  $pesertaList
     */
    public function allocate(Pelatihan $pelatihan, Collection $pesertaList): void
    {
        DB::transaction(function () use ($pelatihan, $pesertaList) {
            // pisahkan berdasarkan gender
            $male   = $pesertaList->where('jenis_kelamin', 'Laki-laki')->values();
            $female = $pesertaList->where('jenis_kelamin', 'Perempuan')->values();

            // ambil semua kamar asrama yang statusnya bukan Rusak
            /** @var Collection<int,Kamar> $kamarAll */
            $kamarAll = Kamar::with('asrama')
                ->where('status', '!=', 'Rusak')
                ->orderBy('asrama_id')
                ->orderBy('lantai')
                ->orderBy('nomor_kamar')
                ->get();

            // 1) alokasi normal (tidak boleh campur gender dalam kamar)
            $maleUnassigned   = $this->assignByGenderStrict($pelatihan, $male, $kamarAll, 'Laki-laki');
            $femaleUnassigned = $this->assignByGenderStrict($pelatihan, $female, $kamarAll, 'Perempuan');

            // 2) fallback: kalau masih ada yang belum dapat kamar
            $remaining = $maleUnassigned->count() + $femaleUnassigned->count();

            if ($remaining > 0) {
                $this->assignFallbackMixed(
                    $pelatihan,
                    $maleUnassigned,
                    $femaleUnassigned,
                    $kamarAll
                );
            }
        });
    }

    protected function assignByGenderStrict(
        Pelatihan $pelatihan,
        Collection $peserta,
        Collection $kamarAll,
        string $gender
    ): Collection {
        $unassigned = collect();

        foreach ($peserta as $p) {
            /** @var Peserta $p */
            $assigned = false;

            foreach ($kamarAll as $kamar) {
                /** @var Kamar $kamar */
                $asrama = $kamar->asrama;

                // Asrama tidak boleh terbalik gendernya, kecuali Campur
                if ($asrama->gender !== 'Campur' && $asrama->gender !== $gender) {
                    continue;
                }

                if ($kamar->available_beds <= 0 || $kamar->status !== 'Tersedia') {
                    continue;
                }

                // cek apakah kamar ini sudah terisi gender lain di riwayat penempatan pelatihan ini
                $existingGender = PenempatanAsrama::where('pelatihan_id', $pelatihan->id)
                    ->where('kamar_id', $kamar->id)
                    ->join('peserta', 'penempatan_asrama.peserta_id', '=', 'peserta.id')
                    ->select('peserta.jenis_kelamin')
                    ->distinct()
                    ->pluck('jenis_kelamin')
                    ->first();

                if ($existingGender && $existingGender !== $gender) {
                    // kamar ini sudah terisi gender lain → skip untuk mode strict
                    continue;
                }

                // assign di sini
                $this->storeAssignment($pelatihan, $p, $asrama, $kamar);
                $assigned = true;

                $kamar->available_beds = max(0, $kamar->available_beds - 1);
                if ($kamar->available_beds === 0) {
                    $kamar->status = 'Penuh';
                }
                $kamar->save();

                break;
            }

            if (! $assigned) {
                $unassigned->push($p);
            }
        }

        return $unassigned;
    }

    protected function assignFallbackMixed(
        Pelatihan $pelatihan,
        Collection $maleUnassigned,
        Collection $femaleUnassigned,
        Collection $kamarAll
    ): void {
        // fallback hanya untuk kamar besar (>= 8 bed)
        $kamarBesar = $kamarAll->filter(function (Kamar $k) {
            return $k->total_beds >= 8 && $k->status === 'Tersedia';
        });

        $queue = $maleUnassigned->concat($femaleUnassigned)->values();

        foreach ($queue as $p) {
            /** @var Peserta $p */
            $assigned = false;

            foreach ($kamarBesar as $kamar) {
                if ($kamar->available_beds <= 0 || $kamar->status !== 'Tersedia') {
                    continue;
                }

                $asrama = $kamar->asrama;

                // di fallback, asrama sebenarnya boleh campur gender,
                // tetapi JIKA asrama.gender *bukan* Campur,
                // ini menandakan aturan darurat (kamar tidak mencukupi).
                // jadi di sini kita tidak cek lagi asrama->gender.

                $this->storeAssignment($pelatihan, $p, $asrama, $kamar);
                $assigned = true;

                $kamar->available_beds = max(0, $kamar->available_beds - 1);
                if ($kamar->available_beds === 0) {
                    $kamar->status = 'Penuh';
                }
                $kamar->save();

                break;
            }

            // kalau sampai sini masih belum assigned → memang benar-benar tidak ada kamar lagi
        }
    }

    protected function storeAssignment(
        Pelatihan $pelatihan,
        Peserta $peserta,
        Asrama $asrama,
        Kamar $kamar
    ): void {
        // simpan ke tabel riwayat penempatan
        PenempatanAsrama::updateOrCreate(
            [
                'pelatihan_id' => $pelatihan->id,
                'peserta_id'   => $peserta->id,
            ],
            [
                'asrama_id'    => $asrama->id,
                'kamar_id'     => $kamar->id,
                'periode'      => $pelatihan->periode ?? null,
            ]
        );

        // kirim email ke peserta (jika email ada)
        if (!empty($peserta->email)) {
            // TODO: buat Mailable InfoKamarMail
            // Mail::to($peserta->email)->queue(new \App\Mail\InfoKamarMail($pelatihan, $peserta, $asrama, $kamar));
        }
    }
}
