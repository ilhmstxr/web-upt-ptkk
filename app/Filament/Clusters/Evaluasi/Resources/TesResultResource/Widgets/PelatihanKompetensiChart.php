<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PelatihanKompetensiChart extends ChartWidget
{
    protected static ?string $heading = 'Rata-Rata per Kompetensi';
    protected int | string | array $columnSpan = 'full';

    public ?int $pelatihanId = null;

    protected function getData(): array
    {
        if (! $this->pelatihanId) {
            return ['datasets' => [], 'labels' => []];
        }

        $rows = DB::table('pendaftaran_pelatihan as pp')
            ->join('kompetensi_pelatihan as kp', 'kp.id', '=', 'pp.kompetensi_pelatihan_id')
            ->join('kompetensi as k', 'k.id', '=', 'kp.kompetensi_id')
            ->where('pp.pelatihan_id', $this->pelatihanId)
            ->select([
                'k.nama_kompetensi',
                DB::raw('ROUND(AVG((pp.nilai_post_test + pp.nilai_praktek) / 2), 2) as rata_avg'),
            ])
            ->groupBy('k.nama_kompetensi')
            ->orderBy('k.nama_kompetensi')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Rata-Rata',
                    'data' => $rows->pluck('rata_avg')->map(fn ($v) => (float) $v)->all(),
                    'backgroundColor' => '#0ea5e9',
                ],
            ],
            'labels' => $rows->pluck('nama_kompetensi')->all(),
        ];
    }

    protected function getPollingInterval(): ?string
    {
        return null;
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

