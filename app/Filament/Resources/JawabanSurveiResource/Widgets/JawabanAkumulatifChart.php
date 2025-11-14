<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\Reactive;


class JawabanAkumulatifChart extends ChartWidget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Akumulatif Skala Likert (1–4)';
    protected int|string|array $columnSpan = '4';

    #[Reactive]
    public ?int $pelatihanId = null;
    protected static bool $isLazy = false;   // penting


    protected function getData(): array
    {
        $pelatihanId   = $this->pelatihanId ?? request()->integer('pelatihanId');
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);

        $base = ['Tidak Memuaskan', 'Kurang Memuaskan', 'Cukup Memuaskan', 'Sangat Memuaskan'];
        $bg = ['rgba(248,113,113,0.7)', 'rgba(251,191,36,0.7)', 'rgba(59,130,246,0.7)', 'rgba(16,185,129,0.7)'];
        $bd = ['rgb(239,68,68)', 'rgb(245,158,11)', 'rgb(59,130,246)', 'rgb(16,185,129)'];

        // hitung count
        $counts = [0, 0, 0, 0];
        if ($pertanyaanIds->isNotEmpty()) {
            [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
            $rows = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);
            foreach ($rows as $r) {
                $s = (int)($r['skala'] ?? 0);
                if ($s >= 1 && $s <= 4) {
                    $counts[$s - 1]++;
                }
            }
        }

        // legend: nama + persen (global)
        $total = array_sum($counts) ?: 1;
        $pct   = array_map(fn($v) => round($v / $total * 100, 1), $counts);
        $fmt   = fn($v) => str_replace('.', ',', number_format($v, 1));
        $labels = [
            "{$base[0]} — {$fmt($pct[0])}%",
            "{$base[1]} — {$fmt($pct[1])}%",
            "{$base[2]} — {$fmt($pct[2])}%",
            "{$base[3]} — {$fmt($pct[3])}%",
        ];

        return [ 
        // $finalData =  [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Jumlah Jawaban',
                'data' => $counts,
                'backgroundColor' => $bg,
                'borderColor' => $bd,
                'borderWidth' => 1,
            ]],
            'options' => [
                'plugins' => [
                    'legend' => ['position' => 'right'],
                    'tooltip' => ['enabled' => true],
                ],
            ],
        ];

        // dd($finalData);
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
