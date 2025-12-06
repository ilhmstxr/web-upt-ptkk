<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\Concerns\BuildsLikertData;
use App\Models\Pertanyaan;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Illuminate\Database\Eloquent\Model;

class JawabanPerKategoriChart extends Widget
{
    use BuildsLikertData;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.widgets.jawaban-per-kategori-chart';

    protected int|string|array $columnSpan = 'full';

    public ?Model $record = null;

    private array $arrayCustom = [
        'Pendapat Tentang Penyelenggaran Pelatihan',
        'Persepsi Terhadap Program Pelatihan',
        'Penilaian Terhadap Instruktur',
    ];

    #[Reactive]
    public ?int $pelatihanId = null;
    protected static bool $isLazy = false;

    public array $chartData = [];

    public function mount(): void
    {
        $pelatihanId = $this->pelatihanId ?? ($this->record?->id ?? request()->integer('pelatihanId'));
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);
        
        if ($pertanyaanIds->isEmpty()) {
            return;
        }

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

        // Calculate Average Score per Category
        $categoryStats = []; // [CategoryName => ['total_score' => 0, 'count' => 0]]

        foreach ($rows as $r) {
            if (!isset($mapKategori[$r['pertanyaan_id']])) {
                continue;
            }
            $cat = $mapKategori[$r['pertanyaan_id']];
            $skala = (int) ($r['skala'] ?? 0);
            
            if ($skala >= 1 && $skala <= 4) {
                if (!isset($categoryStats[$cat])) {
                    $categoryStats[$cat] = ['total_score' => 0, 'count' => 0];
                }
                $categoryStats[$cat]['total_score'] += $skala;
                $categoryStats[$cat]['count']++;
            }
        }

        $labels = [];
        $averages = [];
        $colors = ['#60A5FA', '#FBBF24', '#A78BFA', '#34D399', '#F472B6']; // Blue, Yellow, Purple, Green, Pink

        $i = 0;
        foreach ($categoryStats as $cat => $stats) {
            $avg = $stats['count'] > 0 ? round($stats['total_score'] / $stats['count'], 2) : 0;
            $labels[] = $cat;
            $averages[] = $avg;
            $i++;
        }

        $this->chartData = [
            'labels' => $labels,
            'data' => $averages,
            'colors' => array_slice($colors, 0, count($labels)),
        ];
    }
}
