<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use App\Models\KompetensiPelatihan;
use App\Models\JawabanUser;
use App\Models\Pelatihan;
use App\Models\Pertanyaan;
use App\Models\Tes;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;
use App\Models\OpsiJawaban;

class ViewMonevDetail extends Page
{
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
        $pelatihanId = $this->record->id;
        $kompetensiPelatihanId = $this->kompetensiPelatihan->id;

        // 1. Calculate General Stats (IKM)
        // Accumulate all answers for this competency scope
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId, $kompetensiPelatihanId);

        // FALLBACK: If no specific competency survey found, use the generic training survey
        if ($pertanyaanIds->isEmpty()) {
            $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId, null);
        }

        if ($pertanyaanIds->isEmpty()) {
            $this->surveiData = [];
            return;
        }

        // Build Maps
        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);

        // Get Normalized Answers
        $allAnswers = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId, $kompetensiPelatihanId);

        // Calculate IKM (Average Scale 1-4 converted to 0-100)
        $avgScale = $allAnswers->avg('skala') ?? 0;
        $ikm = ($avgScale / 4) * 100;

        // 2. Build Charts
        $totalChart = $this->buildAkumulatif($pelatihanId, $kompetensiPelatihanId);
        $categoryChart = $this->buildPerKategori($pelatihanId, $kompetensiPelatihanId);

        // 3. Build Question Details grouped by Category
        // Get Question Models to group them
        $questions = Pertanyaan::whereIn('id', $pertanyaanIds)
            ->orderBy('nomor')
            ->get();

        // Manual Categorization Logic (as seen in BuildsLikertData but simplified or reused)
        $mapKategori = $this->buildKategoriMap($pelatihanId, $kompetensiPelatihanId);

        $questionStats = [];
        foreach ($questions as $q) {
            $cat = $mapKategori[$q->id] ?? 'Lainnya';

            $pieData = $this->buildPiePerPertanyaan($pelatihanId, $q->id, $kompetensiPelatihanId);
            if (empty($pieData))
                continue;

            // Format for Blade
            // Blade expects: 'id', 'nomor', 'teks', 'data', 'backgroundColor'
            // pieData returns: 'data' (counts), 'percentages'
            // We need to map colors manually or use same logic
            $colors = ['#f87171', '#fbbf24', '#3b82f6', '#10b981']; // Red, Yellow, Blue, Green

            $questionStats[$cat][] = [
                'id' => $q->id,
                'nomor' => $q->nomor,
                'teks' => $q->teks_pertanyaan,
                'data' => $pieData['data'], // [count1, count2, count3, count4]
                'backgroundColor' => $colors
            ];
        }

        $this->surveiData = [
            'ikm' => number_format($ikm, 1),
            'ikm_category' => $this->getIkmCategory($ikm),
            'total_chart' => $totalChart,
            'category_chart' => $categoryChart,
            'question_stats' => collect($questionStats), // Grouped by category key
            'questions_flat' => collect($questionStats)->flatten(1)->values()->all(),
        ];
    }

    protected function getIkmCategory($val)
    {
        if ($val >= 88)
            return 'SANGAT BAIK';
        if ($val >= 76)
            return 'BAIK';
        if ($val >= 60)
            return 'KURANG BAIK';
        return 'TIDAK BAIK';
    }

    // =========================================================================
    // ADAPTED LOGIC FROM BuildsLikertData trait
    // =========================================================================

    protected function collectPertanyaanIds(?int $pelatihanId, ?int $kompetensiPelatihanId): \Illuminate\Support\Collection
    {
        return JawabanUser::query()
            ->from('jawaban_user as ju')
            ->join('percobaan as pr', 'pr.id', '=', 'ju.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->join('pertanyaan as p', 'p.id', '=', 'ju.pertanyaan_id')
            ->where('t.tipe', 'survei')
            ->where('p.tipe_jawaban', 'skala_likert')
            ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
            // KEY FIX: Filter by Competency
            ->when($kompetensiPelatihanId, fn($q) => $q->where('t.kompetensi_pelatihan_id', $kompetensiPelatihanId))
            ->distinct()
            ->pluck('ju.pertanyaan_id')
            ->values();
    }

    protected function normalizePertanyaanIds(mixed $input): \Illuminate\Support\Collection
    {
        return collect($input)
            ->flatten()
            ->map(fn($v) => is_numeric($v) ? (int) $v : $v)
            ->unique()
            ->values();
    }

    protected function buildLikertMaps($pertanyaanIds): array
    {
        $ids = $this->normalizePertanyaanIds($pertanyaanIds);
        if ($ids->isEmpty())
            return [collect(), collect(), collect()];

        $pivot = DB::table('pivot_jawaban')
            ->whereIn('pertanyaan_id', $ids->all())
            ->pluck('template_pertanyaan_id', 'pertanyaan_id');

        $opsi = \App\Models\OpsiJawaban::whereIn(
            'pertanyaan_id',
            $ids->merge($pivot->values())->unique()->all()
        )->orderBy('id')->get();

        $opsiIdToSkala = $opsi->groupBy('pertanyaan_id')->map(function ($rows) {
            $map = [];
            foreach ($rows->pluck('id')->values() as $i => $id) {
                // Assuming 4 options: 1=Tidak, 2=Kurang, 3=Cukup, 4=Sangat
                $map[$id] = $i + 1;
            }
            return $map;
        });

        $opsiTextToId = $opsi->groupBy('pertanyaan_id')
            ->map(fn($rows) => $rows->pluck('id', 'teks_opsi')->mapWithKeys(
                fn($id, $teks) => [trim($teks) => $id]
            ));

        return [$pivot, $opsiIdToSkala, $opsiTextToId];
    }

    protected function normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId, $kompetensiPelatihanId = null)
    {
        $ids = $this->normalizePertanyaanIds($pertanyaanIds);
        if ($ids->isEmpty())
            return collect();

        $jawaban = JawabanUser::query()
            ->join('percobaan as pr', 'pr.id', '=', 'jawaban_user.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->whereIn('jawaban_user.pertanyaan_id', $ids->all())
            ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
            ->when($kompetensiPelatihanId, fn($q) => $q->where('t.kompetensi_pelatihan_id', $kompetensiPelatihanId))
            ->select([
                'jawaban_user.pertanyaan_id',
                'jawaban_user.opsi_jawaban_id',
                'jawaban_user.jawaban_teks',
            ])
            ->get();

        return $jawaban->map(function ($j) use ($pivot, $opsiIdToSkala, $opsiTextToId) {
            $pid = (int) $j->pertanyaan_id;
            $source = $opsiIdToSkala->get($pid) ? $pid : ($pivot[$pid] ?? $pid);

            $opsiId = $j->opsi_jawaban_id;
            if (!$opsiId && $j->jawaban_teks) {
                $opsiId = optional($opsiTextToId->get($source))->get(trim((string) $j->jawaban_teks));
            }

            $skalaMap = $opsiIdToSkala->get($source, []);
            $skala = $opsiId ? ($skalaMap[$opsiId] ?? null) : null;
            if ($skala !== null) {
                $maxScale = count($skalaMap);
                $skala = $maxScale > 0 ? max(1, min($maxScale, (int) $skala)) : (int) $skala;
            }

            return [
                'pertanyaan_id' => $pid,
                'skala' => $skala,
            ];
        });
    }

    protected function buildAkumulatif($pelatihanId, $kompetensiPelatihanId): array
    {
        $ids = $this->collectPertanyaanIds($pelatihanId, $kompetensiPelatihanId);
        if ($ids->isEmpty())
            return ['datasets' => [['data' => [0, 0, 0, 0], 'backgroundColor' => []]]];

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($ids);
        $rows = $this->normalizedAnswers($pelatihanId, $ids, $pivot, $opsiIdToSkala, $opsiTextToId, $kompetensiPelatihanId);

        $counts = [0, 0, 0, 0];
        foreach ($rows as $r) {
            $skala = (int) ($r['skala'] ?? 0);
            if ($skala >= 1 && $skala <= 4) {
                $counts[$skala - 1]++;
            }
        }

        // Return structure for Blade: total_chart['datasets'][0]['data']
        return [
            'datasets' => [
                [
                    'data' => $counts,
                    'backgroundColor' => ['#f87171', '#fbbf24', '#3b82f6', '#10b981']
                ]
            ]
        ];
    }

    protected function buildPerKategori($pelatihanId, $kompetensiPelatihanId): array
    {
        $ids = $this->collectPertanyaanIds($pelatihanId, $kompetensiPelatihanId);
        if ($ids->isEmpty())
            return ['labels' => [], 'datasets' => []];

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($ids);
        $answers = $this->normalizedAnswers($pelatihanId, $ids, $pivot, $opsiIdToSkala, $opsiTextToId, $kompetensiPelatihanId);
        $mapKategori = $this->buildKategoriMap($pelatihanId, $kompetensiPelatihanId);

        $countsMatrix = [];
        foreach ($answers as $a) {
            $pid = $a['pertanyaan_id'];
            if (!isset($mapKategori[$pid]) || $a['skala'] === null)
                continue;

            $cat = $mapKategori[$pid];
            $countsMatrix[$cat] = $countsMatrix[$cat] ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $sk = (int) $a['skala'];
            if ($sk >= 1 && $sk <= 4)
                $countsMatrix[$cat][$sk]++;
        }

        if (empty($countsMatrix))
            return ['labels' => [], 'datasets' => []];

        $labels = array_keys($countsMatrix);
        $C1 = [];
        $C2 = [];
        $C3 = [];
        $C4 = [];
        foreach ($labels as $cat) {
            $C1[] = $countsMatrix[$cat][1] ?? 0;
            $C2[] = $countsMatrix[$cat][2] ?? 0;
            $C3[] = $countsMatrix[$cat][3] ?? 0;
            $C4[] = $countsMatrix[$cat][4] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Tidak Memuaskan', 'data' => $C1, 'backgroundColor' => '#f87171', 'stack' => 'stack1'],
                ['label' => 'Kurang Memuaskan', 'data' => $C2, 'backgroundColor' => '#fbbf24', 'stack' => 'stack1'],
                ['label' => 'Cukup Memuaskan', 'data' => $C3, 'backgroundColor' => '#3b82f6', 'stack' => 'stack1'],
                ['label' => 'Sangat Memuaskan', 'data' => $C4, 'backgroundColor' => '#10b981', 'stack' => 'stack1'],
            ]
        ];
    }

    protected function buildPiePerPertanyaan($pelatihanId, $pertanyaanId, $kompetensiPelatihanId): array
    {
        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps([$pertanyaanId]);
        $rows = $this->normalizedAnswers($pelatihanId, [$pertanyaanId], $pivot, $opsiIdToSkala, $opsiTextToId, $kompetensiPelatihanId);

        $counts = [0, 0, 0, 0];
        foreach ($rows as $r) {
            $sk = (int) ($r['skala'] ?? 0);
            if ($sk >= 1 && $sk <= 4)
                $counts[$sk - 1]++;
        }

        return [
            'data' => $counts,
        ];
    }

    protected function buildKategoriMap($pelatihanId, $kompetensiPelatihanId): array
    {
        // Get Tes IDs relevant to this competency
        $tesIds = Tes::where('pelatihan_id', $pelatihanId)
            ->where('kompetensi_pelatihan_id', $kompetensiPelatihanId)
            ->where('tipe', 'survei')
            ->pluck('id');

        $questions = Pertanyaan::whereIn('tes_id', $tesIds)
            ->orderBy('tes_id')->orderBy('nomor')
            ->get();

        $mapKategori = [];
        // Determine categories based on "Pesan dan Kesan" separator or default
        // Logic adapted from BuildsLikertData:
        // Group by Tes -> iterate -> split by "pesan dan kesan"

        foreach ($questions->groupBy('tes_id') as $qs) {
            $groupKey = 1;
            $currentBatch = [];
            foreach ($qs as $q) {
                // Check if boundary
                $isBoundary = $q->tipe_jawaban === 'teks_bebas'
                    && \Illuminate\Support\Str::startsWith(strtolower(trim($q->teks_pertanyaan)), 'pesan dan kesan');

                if ($isBoundary) {
                    // Flush current batch to current category
                    $catName = "Kategori $groupKey";
                    foreach ($currentBatch as $item) {
                        if ($item->tipe_jawaban === 'skala_likert') {
                            $mapKategori[$item->id] = $catName;
                        }
                    }
                    $currentBatch = [];
                    $groupKey++;
                }
                $currentBatch[] = $q;
            }
            // Flush remaining
            if (!empty($currentBatch)) {
                $catName = "Kategori $groupKey";
                foreach ($currentBatch as $item) {
                    if ($item->tipe_jawaban === 'skala_likert') {
                        $mapKategori[$item->id] = $catName;
                    }
                }
            }
        }
        return $mapKategori;
    }
}
