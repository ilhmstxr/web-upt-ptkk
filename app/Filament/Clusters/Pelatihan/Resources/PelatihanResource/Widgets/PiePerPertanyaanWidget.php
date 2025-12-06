<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\Concerns\BuildsLikertData;
use App\Models\Pertanyaan;
use Filament\Widgets\Widget;
use Livewire\Attributes\Reactive;
use Illuminate\Database\Eloquent\Model;

class PiePerPertanyaanWidget extends Widget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Detail Jawaban Per Pertanyaan';
    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.widgets.pie-per-pertanyaan-widget';

    public ?Model $record = null;

    #[Reactive]
    public ?int $pelatihanId = null;
    protected static bool $isLazy = false;
    
    public array $chartsByCategory = [];

    public function mount(): void
    {
        $pelatihanId = $this->pelatihanId ?? ($this->record?->id ?? request()->integer('pelatihanId'));
        
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);

        if ($pertanyaanIds->isEmpty()) {
            $this->chartsByCategory = [];
            return;
        }

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $rows = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);

        // Build Category Map
        // We assume 'survei' type for now as this widget is for survey report
        $mapKategori = $this->buildKategoriMap($pelatihanId, 'survei');

        $answersByQuestion = [];
        foreach ($rows as $r) {
            $pid = (int) ($r['pertanyaan_id'] ?? 0);
            $scale = (int) ($r['skala'] ?? 0);

            if ($pid === 0 || $scale === 0) {
                continue;
            }

            $answersByQuestion[$pid][$scale] = ($answersByQuestion[$pid][$scale] ?? 0) + 1;
        }

        $questions = Pertanyaan::whereIn('id', $pertanyaanIds)
            ->where('tipe_jawaban', 'skala_likert')
            ->with([
                'opsiJawabans:id,pertanyaan_id,teks_opsi',
                'templates.opsiJawabans:id,pertanyaan_id,teks_opsi',
            ])
            ->orderBy('tes_id')
            ->orderBy('nomor')
            ->get(['id', 'tes_id', 'nomor', 'teks_pertanyaan']);

        $groupedCharts = [];

        foreach ($questions as $q) {
            $category = $mapKategori[$q->id] ?? 'Lainnya';
            
            $labels = $q->opsiJawabans?->pluck('teks_opsi')->values()->all() ?? [];

            if (empty($labels) && isset($answersByQuestion[$q->id])) {
                $maxScale = (int) max(array_keys($answersByQuestion[$q->id]));
                $labels = array_map(fn(int $idx) => "Skala {$idx}", range(1, $maxScale));
            }

            // Ensure 4 scales for consistency if labels are empty or standard Likert
            if (empty($labels)) {
                $labels = ['Tidak Memuaskan', 'Kurang Memuaskan', 'Cukup Memuaskan', 'Sangat Memuaskan'];
            }

            $counts = [];
            // Assuming standard 4 scale likert for simplicity in visualization as per design
            // If dynamic, we map based on index.
            // Design requires: Sangat Memuaskan (Green), Memuaskan (Blue), Kurang (Yellow), Tidak (Red)
            // Our data is 1..4. 
            // 1=Tidak, 2=Kurang, 3=Cukup, 4=Sangat (Standard assumption)
            
            $val1 = $answersByQuestion[$q->id][4] ?? 0; // Sangat
            $val2 = $answersByQuestion[$q->id][3] ?? 0; // Cukup/Puas
            $val3 = $answersByQuestion[$q->id][2] ?? 0; // Kurang
            $val4 = $answersByQuestion[$q->id][1] ?? 0; // Tidak

            $counts = [$val1, $val2, $val3, $val4];
            $total = array_sum($counts);
            
            $percentages = $total > 0
                ? array_map(fn($c) => round(($c / $total) * 100, 1), $counts)
                : [0, 0, 0, 0];

            $chartData = [
                'question_id'    => $q->id,
                'question_label' => "{$q->nomor}. " . $q->teks_pertanyaan,
                'labels'         => ['Sangat Memuaskan', 'Memuaskan', 'Kurang Memuaskan', 'Tidak Memuaskan'],
                'data'           => $counts,
                'percentages'    => $percentages,
                'colors'         => ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
            ];

            $groupedCharts[$category][] = $chartData;
        }

        // Sort categories if needed (Optional, based on arrayCustom order in other widget)
        // For now, we leave as is or sort by key
        // ksort($groupedCharts); 

        $this->chartsByCategory = $groupedCharts;
    }
}
