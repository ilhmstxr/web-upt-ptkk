<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\Pertanyaan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;


class JawabanPerKategoriChart extends ChartWidget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Distribusi Skala per Kategori';
    protected int|string|array $columnSpan = '8';

    private array $arrayCustom = [
        'Pendapat Tentang Penyelenggaran Pelatihan',
        'Persepsi Terhadap Program Pelatihan',
        'Penilaian Terhadap Instruktur',
    ];


    #[Reactive]
    public ?int $pelatihanId = null;
    protected static bool $isLazy = false;   // penting


    protected function getData(): array
    {
        $pelatihanId   = $this->pelatihanId ?? request()->integer('pelatihanId');
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);
        if ($pertanyaanIds->isEmpty()) return ['labels' => [], 'datasets' => []];

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $rows = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);

        $tesIds = DB::table('percobaan as pr')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
            ->pluck('t.id')->unique()->values();

        $pertanyaanAll = Pertanyaan::whereIn('tes_id', $tesIds)
            ->orderBy('tes_id')->orderBy('nomor')
            ->get(['id', 'tes_id', 'tipe_jawaban', 'teks_pertanyaan', 'nomor']);

        $mapKategori = [];
        foreach ($pertanyaanAll->groupBy('tes_id') as $questions) {
            $groupKey = 1;
            $temp = [];
            foreach ($questions as $q) {
                $temp[] = $q;
                $isBoundary = $q->tipe_jawaban === 'teks_bebas'
                    && str_starts_with(strtolower(trim($q->teks_pertanyaan)), 'pesan dan kesan');
                if ($isBoundary) {
                    $category = $this->arrayCustom[$groupKey - 1] ?? ('Kategori ' . $groupKey);
                    foreach ($temp as $item) {
                        if ($item->tipe_jawaban === 'skala_likert') {
                            $mapKategori[$item->id] = $category;
                        }
                    }
                    $temp = [];
                    $groupKey++;
                }
            }
            if (!empty($temp)) {
                $category = $this->arrayCustom[$groupKey - 1] ?? ('Kategori ' . $groupKey);
                foreach ($temp as $item) {
                    if ($item->tipe_jawaban === 'skala_likert') {
                        $mapKategori[$item->id] = $category;
                    }
                }
            }
        }


        // Hanya akumulasi untuk pertanyaan yang memiliki kategori → SIMPAN COUNT
        $countsMatrix = [];
        foreach ($rows as $r) {
            if (!isset($mapKategori[$r['pertanyaan_id']])) {
                continue; // skip tanpa kategori
            }
            $cat = $mapKategori[$r['pertanyaan_id']];
            $countsMatrix[$cat] = $countsMatrix[$cat] ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            if (!empty($r['skala'])) {
                $countsMatrix[$cat][(int) $r['skala']]++;
            }
        }

        if (empty($countsMatrix)) {
            return ['labels' => [], 'datasets' => []];
        }

        // Turunkan persentase dari COUNT tanpa menghilangkan count
        $percentsMatrix = [];
        $totalsByCat = [];
        foreach ($countsMatrix as $cat => $counts) {
            $total = array_sum($counts);
            $totalsByCat[$cat] = $total;
            $percentsMatrix[$cat] = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

            if ($total > 0) {
                foreach ($counts as $scale => $cnt) {
                    $percentsMatrix[$cat][$scale] = round(($cnt / $total) * 100, 2);
                }
            }
        }

        // Flatten ke array per-dataset
        $labels = array_keys($countsMatrix);
        $C1 = $C2 = $C3 = $C4 = [];
        $P1 = $P2 = $P3 = $P4 = [];
        foreach ($labels as $cat) {
            $C1[] = $countsMatrix[$cat][1] ?? 0;
            $C2[] = $countsMatrix[$cat][2] ?? 0;
            $C3[] = $countsMatrix[$cat][3] ?? 0;
            $C4[] = $countsMatrix[$cat][4] ?? 0;

            $P1[] = $percentsMatrix[$cat][1] ?? 0;
            $P2[] = $percentsMatrix[$cat][2] ?? 0;
            $P3[] = $percentsMatrix[$cat][3] ?? 0;
            $P4[] = $percentsMatrix[$cat][4] ?? 0;
        }

        // ---- setelah Anda punya $C1..$C4 (COUNT per kategori) ----
        $sum = fn(array $a) => array_sum(array_map('intval', $a));
        $t1 = $sum($C1);
        $t2 = $sum($C2);
        $t3 = $sum($C3);
        $t4 = $sum($C4);
        $grand = max(1, $t1 + $t2 + $t3 + $t4); // hindari bagi 0

        $p1 = round($t1 / $grand * 100, 1);
        $p2 = round($t2 / $grand * 100, 1);
        $p3 = round($t3 / $grand * 100, 1);
        $p4 = round($t4 / $grand * 100, 1);

        // opsional: format koma
        $fmt = fn(float $v) => str_replace('.', ',', number_format($v, 1));

        // ---- return chart config ----
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'type' => 'bar',
                    'label' => 'Tidak Memuaskan — ' . $fmt($p1) . '%',
                    'data' => $C1,
                    'yAxisID' => 'y',
                    'stack' => 'count',
                    'backgroundColor' => 'rgba(248,113,113,0.7)',
                    'borderColor' => 'rgb(239,68,68)',
                    'borderWidth' => 1,
                    'order' => 1,
                ],
                [
                    'type' => 'bar',
                    'label' => 'Kurang Memuaskan — ' . $fmt($p2) . '%',
                    'data' => $C2,
                    'yAxisID' => 'y',
                    'stack' => 'count',
                    'backgroundColor' => 'rgba(251,191,36,0.7)',
                    'borderColor' => 'rgb(245,158,11)',
                    'borderWidth' => 1,
                    'order' => 1,
                ],
                [
                    'type' => 'bar',
                    'label' => 'Cukup Memuaskan — ' . $fmt($p3) . '%',
                    'data' => $C3,
                    'yAxisID' => 'y',
                    'stack' => 'count',
                    'backgroundColor' => 'rgba(59,130,246,0.7)',
                    'borderColor' => 'rgb(59,130,246)',
                    'borderWidth' => 1,
                    'order' => 1,
                ],
                [
                    'type' => 'bar',
                    'label' => 'Sangat Memuaskan — ' . $fmt($p4) . '%',
                    'data' => $C4,
                    'yAxisID' => 'y',
                    'stack' => 'count',
                    'backgroundColor' => 'rgba(16,185,129,0.7)',
                    'borderColor' => 'rgb(16,185,129)',
                    'borderWidth' => 1,
                    'order' => 1,
                ],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'interaction' => ['mode' => 'index', 'intersect' => false],
                'scales' => [
                    'x' => ['stacked' => true],
                    'y' => [
                        'stacked' => true,
                        'beginAtZero' => true,
                        'title' => ['display' => true, 'text' => 'Total'],
                    ],
                ],
                'plugins' => [
                    'legend' => ['position' => 'right'], // legend kanan: warna + nama + persen
                    'tooltip' => ['enabled' => true],     // tooltip default menampilkan COUNT
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
