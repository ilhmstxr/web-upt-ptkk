<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResource;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Tes;
use App\Models\Pertanyaan;
use App\Models\Percobaan;
use App\Models\JawabanUser;
use App\Models\PendaftaranPelatihan;

class ViewTes extends ViewRecord
{
    protected static string $resource = TesResource::class;

    // data untuk blade
    public int $mudah = 0;
    public int $sedang = 0;
    public int $sulit = 0;

    public int $totalPeserta = 0;

    public float $avgPre = 0;
    public float $avgPraktek = 0;
    public float $avgPost = 0;
    public float $gain = 0;
    public float $gainPct = 0;

    public int $lulus = 0;
    public int $remedial = 0;
    public float $persenLulus = 0;
    public float $persenRemedial = 0;

    public float $maxPost = 0;
    public float $minPost = 0;
    public float $medianPost = 0;
    public float $stdPost = 0;

    public array $analisisSoal = [];
    public array $rekapNilai = [];

    public function mount(int|string $record): void
    {
        parent::mount($record);

        /** @var Tes $tes */
        $tes = $this->record;

        // ==========================================
        // 1) AMBIL SEMUA PERTANYAAN + HITUNG BENAR/SALAH
        // ==========================================
        $pertanyaanList = $tes->pertanyaan()
            ->withCount([
                // total jawaban untuk soal ini
                'jawabanUsers as total_jawaban',

                // total jawaban benar (opsiJawaban.apakah_benar = true)
                'jawabanUsers as benar_jawaban' => function ($q) {
                    $q->whereHas('opsiJawaban', function ($qq) {
                        $qq->where('apakah_benar', true);
                    });
                },
            ])
            ->orderBy('nomor')
            ->get();

        $analisis = [];
        $mudah = $sedang = $sulit = 0;

        foreach ($pertanyaanList as $idx => $soal) {
            $total = (int) ($soal->total_jawaban ?? 0);
            $benar = (int) ($soal->benar_jawaban ?? 0);
            $salah = max(0, $total - $benar);

            $p = $total > 0 ? $benar / $total : 0;

            if ($p >= 0.70) {
                $kategori = 'Mudah';
                $mudah++;
            } elseif ($p >= 0.30) {
                $kategori = 'Sedang';
                $sedang++;
            } else {
                $kategori = 'Sulit';
                $sulit++;
            }

            $analisis[] = [
                'no' => $soal->nomor ?? ($idx + 1),
                'benar' => $benar,
                'salah' => $salah,
                'p' => $p,
                'kategori' => $kategori,
            ];
        }

        $this->analisisSoal = $analisis;
        $this->mudah = $mudah;
        $this->sedang = $sedang;
        $this->sulit = $sulit;


        // ==========================================
        // 2) REKAP NILAI PESERTA (BERDASARKAN PERCOBAAN)
        // ==========================================
        $percobaanList = Percobaan::with('peserta')
            ->where('tes_id', $tes->id)
            ->whereNotNull('waktu_selesai') // hanya yang selesai
            ->orderBy('created_at')
            ->get();

        $this->totalPeserta = $percobaanList->unique('peserta_id')->count();

        $rekap = [];
        foreach ($percobaanList as $pc) {
            $durasi = null;
            if ($pc->waktu_mulai && $pc->waktu_selesai) {
                $durasi = $pc->waktu_mulai->diffInMinutes($pc->waktu_selesai);
            }

            $rekap[] = [
                'nama'  => $pc->peserta->nama ?? 'Peserta',
                'skor'  => (float) ($pc->skor ?? 0),
                'lulus' => (bool) ($pc->lulus ?? false),
                'durasi'=> $durasi,
            ];
        }
        $this->rekapNilai = $rekap;


        // ==========================================
        // 3) STATISTIK PRE / PRAKTEK / POST
        // sumber: pendaftaran_pelatihan (pelatihan_id sama dengan tes)
        // ==========================================
        $pendaftaran = PendaftaranPelatihan::where('pelatihan_id', $tes->pelatihan_id)->get();

        $N = $pendaftaran->count();

        $this->avgPre     = (float) ($pendaftaran->avg('nilai_pre_test') ?? 0);
        $this->avgPraktek = (float) ($pendaftaran->avg('nilai_praktek') ?? 0);
        $this->avgPost    = (float) ($pendaftaran->avg('nilai_post_test') ?? 0);

        $this->gain    = $this->avgPost - $this->avgPre;
        $this->gainPct = $this->avgPre > 0 ? (($this->gain / $this->avgPre) * 100) : 0;

        $this->lulus    = $pendaftaran->where('nilai_post_test', '>=', 75)->count();
        $this->remedial = $N - $this->lulus;

        $this->persenLulus    = $N > 0 ? ($this->lulus / $N) * 100 : 0;
        $this->persenRemedial = 100 - $this->persenLulus;

        $this->maxPost = (float) ($pendaftaran->max('nilai_post_test') ?? 0);
        $this->minPost = (float) ($pendaftaran->min('nilai_post_test') ?? 0);

        // median
        $vals = $pendaftaran->pluck('nilai_post_test')->filter()->sort()->values();
        $n = $vals->count();
        if ($n > 0) {
            $mid = intdiv($n, 2);
            $this->medianPost = ($n % 2)
                ? (float) $vals[$mid]
                : ((float)$vals[$mid - 1] + (float)$vals[$mid]) / 2;
        } else {
            $this->medianPost = 0;
        }

        // std dev
        if ($n > 0) {
            $mean = $vals->avg();
            $this->stdPost = sqrt($vals->map(fn ($v) => pow($v - $mean, 2))->sum() / $n);
        } else {
            $this->stdPost = 0;
        }
    }

    protected function getViewData(): array
    {
        return [
            'mudah' => $this->mudah,
            'sedang' => $this->sedang,
            'sulit' => $this->sulit,

            'totalPeserta' => $this->totalPeserta,
            'avgPre' => $this->avgPre,
            'avgPraktek' => $this->avgPraktek,
            'avgPost' => $this->avgPost,
            'gain' => $this->gain,
            'gainPct' => $this->gainPct,

            'lulus' => $this->lulus,
            'remedial' => $this->remedial,
            'persenLulus' => $this->persenLulus,
            'persenRemedial' => $this->persenRemedial,

            'maxPost' => $this->maxPost,
            'minPost' => $this->minPost,
            'medianPost' => $this->medianPost,
            'stdPost' => $this->stdPost,

            'analisisSoal' => $this->analisisSoal,
            'rekapNilai' => $this->rekapNilai,
        ];
    }
}
