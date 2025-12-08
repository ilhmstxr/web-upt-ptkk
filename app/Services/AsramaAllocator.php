<?php

namespace App\Services;

use App\Models\Asrama;
use App\Models\Kamar;
use App\Models\Peserta;
use App\Models\Pelatihan;
use App\Models\PenempatanAsrama;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AsramaAllocator
{
    /**
     * Allocate peserta ke kamar asrama untuk sebuah pelatihan.
     *
     * Aturan:
     * 1) Strict: dalam 1 kamar tidak boleh campur jenis_kelamin
     *    kecuali asrama berjenis_kelamin "Campur".
     * 2) Fallback: kalau masih ada sisa peserta, boleh campur jenis_kelamin
     *    hanya di kamar besar (>= 8 bed).
     *
     * @return int jumlah peserta yang berhasil ditempatkan
     */
    public function allocate(Pelatihan $pelatihan, Collection $pesertaList): int
    {
        return DB::transaction(function () use ($pelatihan, $pesertaList) {

            // =========================
            // NORMALISASI JENIS KELAMIN PESERTA
            // =========================
            $pesertaLakiLaki = $pesertaList
                ->filter(fn ($p) => $this->normalizeJenisKelamin($p?->jenis_kelamin) === 'laki-laki')
                ->values();

            $pesertaPerempuan = $pesertaList
                ->filter(fn ($p) => $this->normalizeJenisKelamin($p?->jenis_kelamin) === 'perempuan')
                ->values();

            // legacy / tidak jelas jenis kelamin → nanti masuk queue terakhir
            $pesertaUnknown = $pesertaList
                ->reject(fn ($p) =>
                    in_array($this->normalizeJenisKelamin($p?->jenis_kelamin), ['laki-laki', 'perempuan'], true)
                )
                ->values();

            // =========================
            // AMBIL KAMAR YANG BISA DIPAKAI
            // =========================
            $kamarAll = Kamar::with('asrama')
                ->where('status', 'Tersedia') // hanya kamar tersedia
                ->orderBy('asrama_id')
                ->orderBy('lantai')
                ->orderBy('nomor_kamar')
                ->get();

            $jumlahTerbagi = 0;

            // =========================
            // 1) STRICT BY JENIS KELAMIN (TIDAK CAMPUR DALAM 1 KAMAR)
            // =========================
            [$sisaLakiLaki, $terbagiLakiLaki] =
                $this->assignStrictByJenisKelamin($pelatihan, $pesertaLakiLaki, $kamarAll, 'laki-laki');

            [$sisaPerempuan, $terbagiPerempuan] =
                $this->assignStrictByJenisKelamin($pelatihan, $pesertaPerempuan, $kamarAll, 'perempuan');

            $jumlahTerbagi += $terbagiLakiLaki + $terbagiPerempuan;

            // =========================
            // 2) FALLBACK CAMPUR (HANYA KAMAR BESAR >= 8 BED)
            // =========================
            $queueFallback = $sisaLakiLaki
                ->concat($sisaPerempuan)
                ->concat($pesertaUnknown)
                ->values();

            if ($queueFallback->isNotEmpty()) {
                $jumlahTerbagi += $this->assignFallbackCampur($pelatihan, $queueFallback, $kamarAll);
            }

            return $jumlahTerbagi;
        });
    }

    /**
     * Strict: kamar tidak boleh campur jenis_kelamin,
     * kecuali asrama.jenis_kelamin = "Campur".
     *
     * @return array{0: Collection, 1: int} [sisaPeserta, jumlahTerbagi]
     */
    protected function assignStrictByJenisKelamin(
        Pelatihan $pelatihan,
        Collection $peserta,
        Collection $kamarAll,
        string $jenisKelaminNormalized
    ): array {
        $sisaPeserta = collect();
        $jumlahTerbagi = 0;

        foreach ($peserta as $p) {
            /** @var Peserta $p */
            $sudahTerbagi = false;

            foreach ($kamarAll as $kamar) {
                /** @var Kamar $kamar */
                $asrama = $kamar->asrama;
                if (! $asrama) continue;

                // ✅ pakai kolom yang benar: asrama.jenis_kelamin
                $jenisKelaminAsrama = $this->normalizeJenisKelamin($asrama->jenis_kelamin ?? null);

                // kalau asrama "Campur" → null → bebas
                if ($jenisKelaminAsrama && $jenisKelaminAsrama !== $jenisKelaminNormalized) {
                    continue;
                }

                // cek bed tersedia realtime
                $bedTersedia = $this->getAvailableBeds($pelatihan, $kamar);
                if ($bedTersedia <= 0) continue;

                // kamar strict: tidak boleh isi jenis_kelamin lain dalam pelatihan ini
                $jenisKelaminSudahAdaDiKamar = PenempatanAsrama::where('pelatihan_id', $pelatihan->id)
                    ->where('kamar_id', $kamar->id)
                    ->join('peserta', 'penempatan_asrama.peserta_id', '=', 'peserta.id')
                    ->pluck('peserta.jenis_kelamin')
                    ->map(fn ($jk) => $this->normalizeJenisKelamin($jk))
                    ->filter()
                    ->first();

                if ($jenisKelaminSudahAdaDiKamar && $jenisKelaminSudahAdaDiKamar !== $jenisKelaminNormalized) {
                    continue;
                }

                // assign
                $this->storeAssignment($pelatihan, $p, $asrama, $kamar);

                $sudahTerbagi = true;
                $jumlahTerbagi++;

                $this->decreaseBeds($pelatihan, $kamar);
                break;
            }

            if (! $sudahTerbagi) {
                $sisaPeserta->push($p);
            }
        }

        return [$sisaPeserta, $jumlahTerbagi];
    }

    /**
     * Fallback campur: boleh campur jenis_kelamin,
     * tapi hanya di kamar besar (>= 8 bed).
     */
    protected function assignFallbackCampur(
        Pelatihan $pelatihan,
        Collection $queue,
        Collection $kamarAll
    ): int {
        $jumlahTerbagi = 0;

        $kamarBesar = $kamarAll->filter(fn (Kamar $k) => (int) $k->total_beds >= 8);

        foreach ($queue as $p) {
            /** @var Peserta $p */
            foreach ($kamarBesar as $kamar) {
                $bedTersedia = $this->getAvailableBeds($pelatihan, $kamar);
                if ($bedTersedia <= 0) continue;

                $asrama = $kamar->asrama;
                if (! $asrama) continue;

                $this->storeAssignment($pelatihan, $p, $asrama, $kamar);

                $jumlahTerbagi++;
                $this->decreaseBeds($pelatihan, $kamar);
                break;
            }
        }

        return $jumlahTerbagi;
    }

    /**
     * available beds:
     * - pakai available_beds kalau masih ada nilainya
     * - kalau 0/null, hitung realtime dari total_beds - usedBeds (per pelatihan)
     */
    protected function getAvailableBeds(Pelatihan $pelatihan, Kamar $kamar): int
    {
        if ($kamar->available_beds !== null && (int) $kamar->available_beds > 0) {
            return (int) $kamar->available_beds;
        }

        $totalBeds = (int) ($kamar->total_beds ?? 0);
        if ($totalBeds <= 0) return 0;

        $usedBeds = PenempatanAsrama::where('pelatihan_id', $pelatihan->id)
            ->where('kamar_id', $kamar->id)
            ->count();

        return max($totalBeds - $usedBeds, 0);
    }

    /**
     * Kurangi bed global kamar + update status kalau penuh
     */
    protected function decreaseBeds(Pelatihan $pelatihan, Kamar $kamar): void
    {
        if ($kamar->available_beds === null || (int) $kamar->available_beds <= 0) {
            $kamar->available_beds = $this->getAvailableBeds($pelatihan, $kamar);
        }

        $kamar->available_beds = max(0, (int) $kamar->available_beds - 1);

        if ($kamar->available_beds === 0) {
            $kamar->status = 'Penuh';
        }

        $kamar->save();
    }

    /**
     * Simpan penempatan (pelatihan_id wajib).
     */
    protected function storeAssignment(
        Pelatihan $pelatihan,
        Peserta $peserta,
        Asrama $asrama,
        Kamar $kamar
    ): void {
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
    }

    /**
     * Normalisasi nilai jenis_kelamin dari database lama:
     * - "L", "laki-laki", "pria", "putra" => laki-laki
     * - "P", "perempuan", "wanita", "putri" => perempuan
     * - "Campur" => null (bebas)
     */
    protected function normalizeJenisKelamin(?string $value): ?string
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
