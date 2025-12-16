<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use App\Models\KompetensiPelatihan;
use App\Models\Pelatihan;
use App\Models\Pertanyaan;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

class ViewMonevDetail extends Page
{
    use \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\Concerns\BuildsLikertData;

    protected static string $resource = PelatihanResource::class;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.pages.view-monev-detail';

    public Pelatihan $record;
    public KompetensiPelatihan $kompetensiPelatihan;
    public $surveiData = [];

    public function mount(Pelatihan $record, $kompetensi_id): void
    {
        $this->record = $record;
        $this->kompetensiPelatihan = KompetensiPelatihan::findOrFail($kompetensi_id);

        $this->prepareSurveiData();
    }

    public function getTitle(): string|Htmlable
    {
        return 'Analisis Hasil Survei Monev - ' . ($this->kompetensiPelatihan->kompetensi->nama_kompetensi ?? 'Detail Kompetensi');
    }

    protected function prepareSurveiData()
    {
        // Use the robust logic migrated from ViewKompetensiPelatihan
        // This ensures consistent filtering by Kompetensi ID
        $stats = $this->getCompetencyLikertStats();

        // Map the result to what the updated view expects
        // The previous view expected 'ikm', 'ikm_category', 'total_chart', etc.
        // The new logic returns 'avg', 'count', 'charts' => [...]

        if (empty($stats['charts'])) {
            $this->surveiData = [];
            return;
        }

        $charts = $stats['charts'];

        // Calculate IKM (0-100) from the 5-scale avg if needed, or just use the stats as is.
        // The previous ViewKompetensi logic returned 'avg' as a 0-5 scale (approx).
        // Let's ensure we populate everything the view might need.

        // IKM Calculation: (Avg / 5) * 100 roughly
        $ikm = ($stats['avg'] / 5) * 100;

        $this->surveiData = [
            'ikm' => number_format($ikm, 1),
            'ikm_category' => $this->getIkmCategory($ikm),
            'avg' => $stats['avg'],
            'responden' => $stats['count'],
            'total_chart' => $charts['total_chart'],
            'category_chart' => $charts['category_chart'],
            'question_stats' => $charts['question_stats'],
            // Flatten for JS iteration if needed, though Alpine iterates the robust structure fine
            'questions_flat' => collect($charts['question_stats'])->flatten(1)->values()->all(),
        ];
    }

    protected function getIkmCategory($val)
    {
        if ($val >= 88) return 'SANGAT BAIK';
        if ($val >= 76) return 'BAIK';
        if ($val >= 60) return 'KURANG BAIK';
        return 'TIDAK BAIK';
    }

    protected function getCompetencyLikertStats(): array
    {
        $pelatihanId = $this->record->id;
        $kompetensiId = $this->kompetensiPelatihan->id;

        // 1. Ambil ID Pertanyaan yang relevan
        $pertanyaanIds = \App\Models\JawabanUser::query()
            ->from('jawaban_user as ju')
            ->join('percobaan as pr', 'pr.id', '=', 'ju.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->join('pertanyaan as p', 'p.id', '=', 'ju.pertanyaan_id')
            ->join('peserta as pst', 'pst.id', '=', 'pr.peserta_id')
            ->join('pendaftaran_pelatihan as pp', 'pp.peserta_id', '=', 'pst.id')
            ->where('t.tipe', 'survei')
            ->where('p.tipe_jawaban', 'skala_likert')
            ->where('pp.pelatihan_id', $pelatihanId)
            ->where('pp.kompetensi_pelatihan_id', $kompetensiId)
            ->distinct()
            ->pluck('ju.pertanyaan_id');

        if ($pertanyaanIds->isEmpty()) {
            return ['avg' => 0, 'count' => 0, 'charts' => []];
        }

        // 2. Build Maps
        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);

        // 3. Ambil Jawaban Filtered
        $jawabanQuery = \App\Models\JawabanUser::query()
            ->join('percobaan as pr', 'pr.id', '=', 'jawaban_user.percobaan_id')
            ->join('peserta as pst', 'pst.id', '=', 'pr.peserta_id')
            ->join('pendaftaran_pelatihan as pp', 'pp.peserta_id', '=', 'pst.id')
            ->whereIn('jawaban_user.pertanyaan_id', $pertanyaanIds)
            ->where('pp.pelatihan_id', $pelatihanId)
            ->where('pp.kompetensi_pelatihan_id', $kompetensiId)
            ->select([
                'jawaban_user.pertanyaan_id',
                'jawaban_user.opsi_jawaban_id',
                'jawaban_user.jawaban_teks',
                'pr.peserta_id'
            ])
            ->get();

        // 4. Hitung Statistik
        $totalSkala = 0;
        $countItems = 0;
        $uniqueRespondents = $jawabanQuery->pluck('peserta_id')->unique()->count();

        // Chart Data Containers
        $totalCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $categoryCounts = [];
        $questionCounts = [];

        $allPertanyaan = \App\Models\Pertanyaan::whereIn('id', $pertanyaanIds)->get()->keyBy('id');

        foreach ($jawabanQuery as $j) {
            $pid = (int) $j->pertanyaan_id;
            $source = $opsiIdToSkala->get($pid) ? $pid : ($pivot[$pid] ?? $pid);

            $opsiId = $j->opsi_jawaban_id;
            if (!$opsiId && $j->jawaban_teks) {
                $opsiId = optional($opsiTextToId->get($source))->get(trim((string) $j->jawaban_teks));
            }

            $skalaMap = $opsiIdToSkala->get($source, []);
            $skala = $opsiId ? ($skalaMap[$opsiId] ?? null) : null;

            if ($skala) {
                $maxScale = count($skalaMap);
                $val = $maxScale > 0 ? max(1, min($maxScale, (int) $skala)) : (int) $skala;

                // Accumulate Avg
                $totalSkala += $val;
                $countItems++;

                // Build Charts Data
                if ($val >= 1 && $val <= 4) {
                    // 1. Total
                    $totalCounts[$val]++;

                    $p = $allPertanyaan->get($pid);
                    $kategori = $p && !empty($p->kategori) ? $p->kategori : 'Umum';

                    // 2. Category
                    if (!isset($categoryCounts[$kategori])) {
                        $categoryCounts[$kategori] = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                    }
                    $categoryCounts[$kategori][$val]++;

                    // 3. Question
                    if (!isset($questionCounts[$pid])) {
                        $questionCounts[$pid] = [
                            'id' => $pid,
                            'teks' => $p->teks_pertanyaan ?? 'Pertanyaan #' . $pid,
                            'kategori' => $kategori,
                            'counts' => [1 => 0, 2 => 0, 3 => 0, 4 => 0]
                        ];
                    }
                    $questionCounts[$pid]['counts'][$val]++;
                }
            }
        }

        $avgSkala = $countItems > 0 ? ($totalSkala / $countItems) : 0;
        // Convert to 5-scale equivalent for consistency with other parts if needed
        // Assuming original scale is 1-4.
        $score5 = ($avgSkala / 4) * 5;

        // Prepare Chart Structures
        $totalChart = [
            'labels' => ['Tidak Puas', 'Kurang Puas', 'Cukup Puas', 'Sangat Puas'],
            'datasets' => [[
                'data' => [$totalCounts[1], $totalCounts[2], $totalCounts[3], $totalCounts[4]],
                'backgroundColor' => ['#ef4444', '#f97316', '#3b82f6', '#22c55e']
            ]]
        ];

        $catLabels = array_keys($categoryCounts);
        $dataset1 = [];
        $dataset2 = [];
        $dataset3 = [];
        $dataset4 = [];
        foreach ($catLabels as $cat) {
            $dataset1[] = $categoryCounts[$cat][1];
            $dataset2[] = $categoryCounts[$cat][2];
            $dataset3[] = $categoryCounts[$cat][3];
            $dataset4[] = $categoryCounts[$cat][4];
        }
        $categoryChart = [
            'labels' => $catLabels,
            'datasets' => [
                ['label' => 'Tidak Puas', 'data' => $dataset1, 'backgroundColor' => '#ef4444'],
                ['label' => 'Kurang Puas', 'data' => $dataset2, 'backgroundColor' => '#f97316'],
                ['label' => 'Cukup Puas', 'data' => $dataset3, 'backgroundColor' => '#3b82f6'],
                ['label' => 'Sangat Puas', 'data' => $dataset4, 'backgroundColor' => '#22c55e'],
            ]
        ];

        $groupedQuestions = collect($questionCounts)->groupBy('kategori')->map(function ($group) {
            return $group->map(function ($q) {
                $q['total_responden'] = array_sum($q['counts']);
                return $q;
            })->values();
        })->toArray();

        return [
            'avg' => $score5,
            'count' => $uniqueRespondents,
            'charts' => [
                'total_chart' => $totalChart,
                'category_chart' => $categoryChart,
                'question_stats' => $groupedQuestions
            ]
        ];
    }
}
