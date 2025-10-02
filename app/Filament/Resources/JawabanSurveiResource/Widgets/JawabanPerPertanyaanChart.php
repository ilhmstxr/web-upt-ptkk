<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use Filament\Widgets\ChartWidget;

class JawabanPerPertanyaanChart extends ChartWidget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Distribusi Skala per Pertanyaan';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $pelatihanId   = request()->integer('pelatihanId');
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);
        if ($pertanyaanIds->isEmpty()) return ['labels'=>[],'datasets'=>[]];

        [$pivot,$opsiIdToSkala,$opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $rows = $this->normalizedAnswers($pelatihanId,$pertanyaanIds,$pivot,$opsiIdToSkala,$opsiTextToId);

        $matrix = [];
        foreach ($pertanyaanIds as $pid) $matrix[$pid] = [1=>0,2=>0,3=>0,4=>0];
        foreach ($rows as $r) if (!empty($r['skala'])) $matrix[(int)$r['pertanyaan_id']][(int)$r['skala']]++;

        $labels = $pertanyaanIds->map(fn($id) => "Q$id")->values()->all();
        $d1=$d2=$d3=$d4=[];
        foreach ($pertanyaanIds as $pid) {
            $d1[] = $matrix[$pid][1] ?? 0;
            $d2[] = $matrix[$pid][2] ?? 0;
            $d3[] = $matrix[$pid][3] ?? 0;
            $d4[] = $matrix[$pid][4] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label'=>'Skala 1','data'=>$d1],
                ['label'=>'Skala 2','data'=>$d2],
                ['label'=>'Skala 3','data'=>$d3],
                ['label'=>'Skala 4','data'=>$d4],
            ],
            'options' => ['scales'=>['x'=>['stacked'=>true],'y'=>['stacked'=>true]]],
        ];
    }

    protected function getType(): string { return 'bar'; }
}
