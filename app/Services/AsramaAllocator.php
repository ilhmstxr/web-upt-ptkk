<?php

namespace App\Services;

use App\Models\Asrama;
use App\Models\Kamar;
use App\Models\Peserta;
use App\Models\Pelatihan;
use App\Models\PenempatanAsrama;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AsramaAllocator
{
    /**
     * RE-ALLOCATE TOTAL:
     * - hapus penempatan lama pelatihan ini
     * - reset available_beds = total_beds untuk semua kamar non-rusak
     * - bagi ulang dari nol sesuai aturan
     */
    public function reallocate(Pelatihan $pelatihan, Collection $pesertaList): int
    {
        return DB::transaction(function () use ($pelatihan, $pesertaList) {

            // 0) hapus penempatan lama pelatihan ini
            PenempatanAsrama::where('pelatihan_id', $pelatihan->id)->delete();

            // 1) reset bed kamar (biar hitungan selalu fresh)
            Kamar::query()
                ->where('status', '!=', 'Rusak')
                ->update([
                    'available_beds' => DB::raw('total_beds'),
                    'status'         => 'Tersedia',
                ]);

            // 2) lanjut allocate normal
            return $this->allocateFresh($pelatihan, $pesertaList);
        });
    }

    /**
     * Allocate dari kondisi fresh (tanpa penempatan lama).
     * 3 tahap:
     * 1) Tahap Asrama single jenis_kelamin (lock per asrama).
     * 2) Tahap campur boleh dalam 1 asrama, tetapi kamar tetap 1 jenis_kelamin.
     * 3) Tahap darurat: kamar besar >= 8 bed boleh campur dalam 1 kamar.
     */
    protected function allocateFresh(Pelatihan $pelatihan, Collection $pesertaList): int
    {
        // =========================
        // NORMALISASI JENIS KELAMIN PESERTA
        // =========================
        $laki = $pesertaList
            ->filter(fn ($p) => $this->normJK($p?->jenis_kelamin) === 'laki-laki')
            ->values();

        $perem = $pesertaList
            ->filter(fn ($p) => $this->normJK($p?->jenis_kelamin) === 'perempuan')
            ->values();

        $unknown = $pesertaList
            ->reject(fn ($p) => in_array($this->normJK($p?->jenis_kelamin), ['laki-laki', 'perempuan'], true))
            ->values();

        // =========================
        // AMBIL KAMAR NON RUSAK & BED POSITIF
        // =========================
        $kamarAll = Kamar::with('asrama')
            ->where('status', '!=', 'Rusak')
            ->where('total_beds', '>', 0)
            ->orderBy('asrama_id')
            ->orderBy('lantai')
            ->orderBy('nomor_kamar')
            ->get();

        if ($kamarAll->isEmpty()) {
            return 0; // memang gak ada kamar valid
        }

        $count = 0;

        // =========================
        // TAHAP 1: 1 asrama hanya 1 jenis_kelamin
        // =========================
        $asramaLock = collect(); // asrama_id => 'laki-laki' / 'perempuan'

        [$sisaLaki, $t1Laki]   = $this->assignStage1($pelatihan, $laki,  $kamarAll, 'laki-laki', $asramaLock);
        [$sisaPerem, $t1Perem] = $this->assignStage1($pelatihan, $perem, $kamarAll, 'perempuan', $asramaLock);

        $count += $t1Laki + $t1Perem;

        // =========================
        // TAHAP 2: asrama boleh campur, kamar tetap 1 gender
        // =========================
        $queue2 = $sisaLaki->concat($sisaPerem)->concat($unknown)->values();
        if ($queue2->isNotEmpty()) {
            $count += $this->assignStage2($pelatihan, $queue2, $kamarAll);
        }

        // =========================
        // TAHAP 3: darurat kamar besar >= 8 bed boleh campur kamar
        // =========================
        $sisa3 = $this->getUnassigned($pelatihan, $pesertaList);
        if ($sisa3->isNotEmpty()) {
            $count += $this->assignStage3($pelatihan, $sisa3, $kamarAll);
        }

        return $count;
    }

    /**
     * Stage 1:
     * Lock asrama ke 1 jenis_kelamin.
     */
    protected function assignStage1(
        Pelatihan $pelatihan,
        Collection $peserta,
        Collection $kamarAll,
        string $jk,
        Collection $asramaLock
    ): array {
        $unassigned = collect();
        $count = 0;

        foreach ($peserta as $p) {
            $assigned = false;

            foreach ($kamarAll as $kamar) {
                $asrama = $kamar->asrama;
                if (! $asrama) continue;

                // asrama rule berdasarkan kolom jenis_kelamin
                $jkAsrama = $this->normJK($asrama->jenis_kelamin ?? null); // null = campur bebas
                if ($jkAsrama && $jkAsrama !== $jk) continue;

                // lock rule: kalau asrama sudah dipakai gender lain → skip tahap 1
                if ($asramaLock->has($asrama->id) && $asramaLock[$asrama->id] !== $jk) {
                    continue;
                }

                if ($kamar->available_beds <= 0) continue;

                // kamar tidak boleh campur dalam tahap ini
                if ($this->roomHasOtherJK($pelatihan, $kamar, $jk)) continue;

                $this->store($pelatihan, $p, $asrama, $kamar);
                $this->decBed($kamar);

                // set lock asrama
                $asramaLock[$asrama->id] = $jk;

                $assigned = true;
                $count++;
                break;
            }

            if (! $assigned) $unassigned->push($p);
        }

        return [$unassigned, $count];
    }

    /**
     * Stage 2:
     * asrama boleh campur,
     * kamar tetap 1 jenis_kelamin.
     */
    protected function assignStage2(
        Pelatihan $pelatihan,
        Collection $queue,
        Collection $kamarAll
    ): int {
        $count = 0;

        foreach ($queue as $p) {
            $jk = $this->normJK($p?->jenis_kelamin);

            foreach ($kamarAll as $kamar) {
                $asrama = $kamar->asrama;
                if (! $asrama) continue;
                if ($kamar->available_beds <= 0) continue;

                // kamar tetap gak boleh campur jenis_kelamin
                if ($jk && $this->roomHasOtherJK($pelatihan, $kamar, $jk)) {
                    continue;
                }

                $this->store($pelatihan, $p, $asrama, $kamar);
                $this->decBed($kamar);

                $count++;
                break;
            }
        }

        return $count;
    }

    /**
     * Stage 3:
     * Darurat: kamar besar >= 8 bed,
     * boleh campur dalam 1 kamar.
     */
    protected function assignStage3(
        Pelatihan $pelatihan,
        Collection $queue,
        Collection $kamarAll
    ): int {
        $count = 0;

        $kamarBesar = $kamarAll->filter(fn ($k) => (int) $k->total_beds >= 8);

        foreach ($queue as $p) {
            foreach ($kamarBesar as $kamar) {
                if ($kamar->available_beds <= 0) continue;

                $asrama = $kamar->asrama;
                if (! $asrama) continue;

                $this->store($pelatihan, $p, $asrama, $kamar);
                $this->decBed($kamar);

                $count++;
                break;
            }
        }

        return $count;
    }

    /**
     * ✅ FIX MULTI-DB:
     * Cek apakah kamar sudah terisi jenis_kelamin lain dalam pelatihan ini
     * TANPA join tabel peserta (hindari cross-connection).
     */
    protected function roomHasOtherJK(Pelatihan $pelatihan, Kamar $kamar, string $jk): bool
    {
        $pesertaIds = PenempatanAsrama::where('pelatihan_id', $pelatihan->id)
            ->where('kamar_id', $kamar->id)
            ->pluck('peserta_id');

        if ($pesertaIds->isEmpty()) {
            return false;
        }

        $existingJK = Peserta::whereIn('id', $pesertaIds)
            ->pluck('jenis_kelamin')
            ->map(fn ($x) => $this->normJK($x))
            ->filter()
            ->first();

        return $existingJK && $existingJK !== $jk;
    }

    /**
     * Ambil peserta yang belum punya penempatan setelah tahap 1+2.
     */
    protected function getUnassigned(Pelatihan $pelatihan, Collection $allPeserta): Collection
    {
        $placedIds = PenempatanAsrama::where('pelatihan_id', $pelatihan->id)
            ->pluck('peserta_id')
            ->unique();

        return $allPeserta->reject(fn ($p) => $placedIds->contains($p->id))->values();
    }

    protected function store(Pelatihan $pelatihan, Peserta $peserta, Asrama $asrama, Kamar $kamar): void
    {
        PenempatanAsrama::updateOrCreate(
            [
                'pelatihan_id' => $pelatihan->id,
                'peserta_id'   => $peserta->id,
            ],
            [
                'asrama_id' => $asrama->id,
                'kamar_id'  => $kamar->id,
                'periode'   => $pelatihan->periode ?? null,
            ]
        );
    }

    protected function decBed(Kamar $kamar): void
    {
        $kamar->available_beds = max(0, (int) $kamar->available_beds - 1);

        if ($kamar->available_beds === 0) {
            $kamar->status = 'Penuh';
        }

        $kamar->save();
    }

    /**
     * Normalisasi jenis_kelamin.
     */
    protected function normJK(?string $value): ?string
    {
        $v = strtolower(trim($value ?? ''));
        if ($v === '') return null;

        if (in_array($v, ['l', 'laki-laki', 'laki laki', 'pria', 'putra', 'male'], true)) {
            return 'laki-laki';
        }

        if (in_array($v, ['p', 'perempuan', 'wanita', 'putri', 'female'], true)) {
            return 'perempuan';
        }

        if ($v === 'campur') return null;

        return null;
    }
}
