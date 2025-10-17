<?php

namespace App\Filament\Widgets;

use App\Models\Percobaan;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class SkorPrePostChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Perbandingan Skor';
    public ?Model $record = null;

    protected function getData(): array
    {
        if (!$this->record) return [];

        // Ambil semua percobaan yang tes-nya terhubung ke pelatihan ini
        $percobaan = Percobaan::whereHas('tes', function ($query) {
            $query->where('pelatihan_id', $this->record->id);
        })
            ->with(['peserta', 'tes'])
            ->get();

        // Kelompokkan data berdasarkan tipe tes ('Pre-Test' atau 'Post-Test')
        $preTestScores = $percobaan->where('tes.tipe', 'Pre-Test')->pluck('skor', 'peserta.nama_peserta');
        $postTestScores = $percobaan->where('tes.tipe', 'Post-Test')->pluck('skor', 'peserta.nama_peserta');
        $labels = $percobaan->pluck('peserta.nama_peserta')->unique()->values();

        return [
            'datasets' => [
                [
                    'label' => 'Pre-Test',
                    'data' => $labels->map(fn($label) => $preTestScores[$label] ?? 0)->toArray(),
                    'borderColor' => '#FBBF24',
                ],
                [
                    'label' => 'Post-Test',
                    'data' => $labels->map(fn($label) => $postTestScores[$label] ?? 0)->toArray(),
                    'borderColor' => '#34D399',
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
