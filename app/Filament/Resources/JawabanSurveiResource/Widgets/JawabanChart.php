<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\JawabanUser;
use App\Models\Pelatihan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JawabanChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Jawaban Berdasarkan Level Opsi';
    public ?Pelatihan $pelatihan = null;

    // ▼▼▼ BARIS columnSpan SUDAH DIHAPUS DARI SINI ▼▼▼

    protected function getData(): array
    {
        if (is_null($this->pelatihan)) {
            return [];
        }

        // 1. Definisikan ID Pertanyaan mana saja yang akan diaggregasi.
        $pertanyaanIds = [81, 98, 103, 104, 106, 109, 112, 113];

        // 2. Definisikan label generik untuk chart
        $genericLabels = [
            1 => 'Tidak Puas',
            2 => 'Kurang Puas',
            3 => 'Puas',
            4 => 'Sangat Puas',
        ];

        // 3. Query utama untuk mengagregasi data
        $rankedOpsiSubquery = DB::table('opsi_jawaban')
            ->select(
                'id',
                DB::raw('ROW_NUMBER() OVER (PARTITION BY pertanyaan_id ORDER BY id ASC) as level_opsi')
            )
            ->whereIn('pertanyaan_id', $pertanyaanIds);

        $aggregatedData = JawabanUser::query()
            ->joinSub($rankedOpsiSubquery, 'ranked_opsi', function ($join) {
                $join->on('jawaban_user.opsi_jawaban_id', '=', 'ranked_opsi.id');
            })
            ->join('percobaan', 'jawaban_user.percobaan_id', '=', 'percobaan.id')
            ->join('peserta', 'percobaan.peserta_id', '=', 'peserta.id')
            ->where('peserta.pelatihan_id', $this->pelatihan->id)
            ->selectRaw('ranked_opsi.level_opsi, count(*) as total')
            ->groupBy('ranked_opsi.level_opsi')
            ->orderBy('ranked_opsi.level_opsi')
            ->get()
            ->keyBy('level_opsi');

        // 4. Siapkan data untuk ditampilkan di chart
        $chartData = [];
        $chartLabels = [];

        foreach ($genericLabels as $level => $label) {
            $chartLabels[] = $label;
            $chartData[] = $aggregatedData->get($level)?->total ?? 0;
        }

        if (empty(array_filter($chartData))) {
            return [];
        }

        // 5. Kembalikan struktur data yang dibutuhkan Chart.js
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Jawaban Terpilih',
                    'data' => $chartData,
                    'backgroundColor' => ['#FF6384', '#FFCE56', '#4BC0C0', '#36A2EB'],
                ],
            ],
            'labels' => $chartLabels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
