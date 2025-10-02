<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use Filament\Widgets\ChartWidget;

class JawabanAkumulatifChart extends ChartWidget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Akumulatif Skala Likert (1–4)';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $pelatihanId = request()->integer('pelatihanId');
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);

        if ($pertanyaanIds->isEmpty()) {
            return ['labels'=>['Skala 1','Skala 2','Skala 3','Skala 4'],
                'datasets'=>[['label'=>'Jumlah','data'=>[0,0,0,0]]]];
        }

        [$pivot,$opsiIdToSkala,$opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $rows = $this->normalizedAnswers($pelatihanId,$pertanyaanIds,$pivot,$opsiIdToSkala,$opsiTextToId);

        $counts = [1=>0,2=>0,3=>0,4=>0];
        foreach ($rows as $r) if (!empty($r['skala'])) $counts[$r['skala']]++;

        return [
            'labels' => ['Skala 1','Skala 2','Skala 3','Skala 4'],
            'datasets' => [[ 'label' => 'Jumlah Jawaban', 'data' => [
                $counts[1], $counts[2], $counts[3], $counts[4],
            ]]],
        ];
    }

    protected function getType(): string { return 'pie'; }
}
