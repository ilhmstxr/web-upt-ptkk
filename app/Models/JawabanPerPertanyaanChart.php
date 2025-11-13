<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\JawabanUser;
use App\Models\Pertanyaan;
use Filament\Widgets\ChartWidget;

class JawabanPerPertanyaanChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Jawaban per Pertanyaan';

    public ?int $pertanyaanId = null; // buat filter pertanyaan

    protected function getData(): array
    {
        // default ambil pertanyaan pertama kalau belum dipilih
        $pertanyaanId = $this->pertanyaanId ?? Pertanyaan::query()->value('id');

        $pertanyaan = Pertanyaan::find($pertanyaanId);

        if (! $pertanyaan) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // hitung distribusi jawaban berdasarkan opsi_jawaban_id
        $jawaban = JawabanUser::query()
            ->where('pertanyaan_id', $pertanyaanId)
            ->selectRaw('opsi_jawaban_id, COUNT(*) as total')
            ->groupBy('opsi_jawaban_id')
            ->get();

        $labels = [];
        $values = [];

        foreach ($jawaban as $j) {
            $labels[] = optional($j->opsiJawaban)->teks_opsi ?? 'Teks';
            $values[] = $j->total;
        }

        return [
            'datasets' => [
                [
                    'label' => $pertanyaan->teks_pertanyaan,
                    'data' => $values,
                    'backgroundColor' => [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
