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
                DB::raw('ROUND(AVG(pp.nilai_pre_test), 2) as pre_avg'),
                DB::raw('ROUND(AVG(pp.nilai_post_test), 2) as post_avg'),
                DB::raw('ROUND(AVG(pp.nilai_praktek), 2) as praktek_avg'),
                DB::raw('ROUND(AVG((pp.nilai_post_test + pp.nilai_praktek) / 2), 2) as rata_avg'),
            ])
            ->groupBy('k.nama_kompetensi')
            ->orderBy('k.nama_kompetensi')
            ->get();

        $labels = ['Pre-Test', 'Post-Test', 'Praktek', 'Rata-Rata'];
        $colors = [
            '#1d4ed8',
            '#f97316',
            '#7f1d1d',
            '#3b82f6',
            '#d4b483',
            '#0f766e',
            '#6d28d9',
            '#16a34a',
            '#e11d48',
            '#0ea5e9',
        ];

        $datasets = [];
        foreach ($rows as $idx => $row) {
            $color = $colors[$idx % count($colors)];
            $datasets[] = [
                'label' => $row->nama_kompetensi,
                'data' => [
                    (float) $row->pre_avg,
                    (float) $row->post_avg,
                    (float) $row->praktek_avg,
                    (float) $row->rata_avg,
                ],
                'borderColor' => $color,
                'backgroundColor' => $color,
                'borderWidth' => 2,
                'tension' => 0.35,
                'fill' => false,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getPollingInterval(): ?string
    {
        return null;
    }

    protected function getType(): string
    {
        return 'line';
    }
}

