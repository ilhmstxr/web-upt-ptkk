<?php

use Filament\Widgets\ChartWidget;
use App\Models\Percobaan;

class PrePostTestChart extends ChartWidget
{
    public ?\App\Models\Pelatihan $record = null;

    protected static ?string $heading = 'Perbandingan Pre-test & Post-test';

    protected function getData(): array
    {
        $pelatihanId = $this->record->id;

        $pre = Percobaan::where('pelatihan_id', $pelatihanId)
            ->where('tipe', 'pretest')
            ->avg('skor') ?? 0;

        $post = Percobaan::where('pelatihan_id', $pelatihanId)
            ->where('tipe', 'posttest')
            ->avg('skor') ?? 0;

        return [
            'datasets' => [
                [
                    'label' => 'Nilai',
                    'data' => [round($pre, 1), round($post, 1)],
                ],
            ],
            'labels' => ['Pre-Test', 'Post-Test'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
