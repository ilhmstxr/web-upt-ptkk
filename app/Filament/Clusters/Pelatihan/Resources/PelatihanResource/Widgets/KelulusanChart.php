<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;

class KelulusanChart extends ChartWidget
{
    public ?\App\Models\Pelatihan $record = null;

    protected static ?string $heading = 'Kelulusan Peserta';

    protected function getData(): array
    {
        $pelatihanId = $this->record->id;

        $lulus = Percobaan::where('pelatihan_id', $pelatihanId)
            ->where('tipe', 'posttest')
            ->where('lulus', true)
            ->count();

        $tidak = Percobaan::where('pelatihan_id', $pelatihanId)
            ->where('tipe', 'posttest')
            ->where('lulus', false)
            ->count();

        return [
            'datasets' => [
                [
                    'data' => [$lulus, $tidak],
                ],
            ],
            'labels' => ['Lulus', 'Tidak Lulus'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
