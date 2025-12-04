<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\Concerns\BuildsLikertData;
use Filament\Widgets\Widget;
use Livewire\Attributes\Reactive;
use Illuminate\Database\Eloquent\Model;

class JawabanAkumulatifChart extends Widget
{
    use BuildsLikertData;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.widgets.jawaban-akumulatif-chart';

    protected int|string|array $columnSpan = 'full';

    public ?Model $record = null;

    #[Reactive]
    public ?int $pelatihanId = null;
    protected static bool $isLazy = false;

    public array $chartData = [];
    public float $ikmScore = 0;
    public string $ikmCategory = '-';

    public function mount(): void
    {
        $pelatihanId = $this->pelatihanId ?? ($this->record?->id ?? request()->integer('pelatihanId'));
        
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);

        // Init counts: [Sangat Memuaskan, Memuaskan, Kurang Memuaskan, Tidak Memuaskan]
        // Note: Skala 4 = Sangat Memuaskan, Skala 1 = Tidak Memuaskan
        // We want order: 4, 3, 2, 1 for display usually, but let's stick to 1-4 index for calculation first.
        $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        
        if ($pertanyaanIds->isNotEmpty()) {
            [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
            $rows = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);
            foreach ($rows as $r) {
                $s = (int)($r['skala'] ?? 0);
                if ($s >= 1 && $s <= 4) {
                    $counts[$s]++;
                }
            }
        }

        $totalResponses = array_sum($counts);
        
        // Calculate IKM (Indeks Kepuasan Masyarakat) - Simplified version
        // Formula: (Total Score / (Max Score * Total Responses)) * 100
        // Total Score = (n1*1 + n2*2 + n3*3 + n4*4)
        $totalScore = ($counts[1] * 1) + ($counts[2] * 2) + ($counts[3] * 3) + ($counts[4] * 4);
        $maxPossibleScore = $totalResponses * 4;
        
        $this->ikmScore = $maxPossibleScore > 0 ? ($totalScore / $maxPossibleScore) * 100 : 0;
        $this->ikmScore = round($this->ikmScore, 2);

        // Determine Category
        if ($this->ikmScore >= 88.31) $this->ikmCategory = 'SANGAT BAIK';
        elseif ($this->ikmScore >= 76.61) $this->ikmCategory = 'BAIK';
        elseif ($this->ikmScore >= 65) $this->ikmCategory = 'KURANG BAIK';
        else $this->ikmCategory = 'TIDAK BAIK';

        // Prepare Chart Data (Doughnut)
        // Order for Chart: Sangat Memuaskan (4), Memuaskan (3), Kurang (2), Tidak (1)
        $chartCounts = [$counts[4], $counts[3], $counts[2], $counts[1]];
        
        // Calculate percentages for display
        $percentages = array_map(fn($c) => $totalResponses > 0 ? round(($c / $totalResponses) * 100, 1) : 0, $chartCounts);

        $this->chartData = [
            'counts' => $chartCounts,
            'percentages' => $percentages,
            'labels' => ['Sangat Memuaskan', 'Memuaskan', 'Kurang Memuaskan', 'Tidak Memuaskan'],
            'colors' => ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'], // Green, Blue, Yellow, Red
        ];
    }
}
