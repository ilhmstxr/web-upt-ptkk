<?php

namespace App\Filament\Widgets;

use App\Models\Pelatihan;
use App\Models\Percobaan;
use App\Models\Tes;
use Filament\Widgets\ChartWidget;

class HasilKeseluruhanChart extends ChartWidget
{

    public Pelatihan $record; // Menerima data pelatihan
    protected static ?string $heading = 'Partisipasi Peserta';

    protected function getData(): array
    {
        // 1. Cari ID untuk setiap jenis tes di pelatihan ini
        $tesPre = Tes::where('pelatihan_id', $this->record->id)->where('tipe', 'pre-test')->first();
        $tesPost = Tes::where('pelatihan_id', $this->record->id)->where('tipe', 'post-test')->first();
        $tesSurvey = Tes::where('pelatihan_id', $this->record->id)->where('tipe', 'survey')->first();

        // 2. Hitung peserta unik yang sudah mengerjakan berdasarkan ID tes
        $sudahPreTest = $tesPre ? Percobaan::where('tes_id', $tesPre->id)->distinct('peserta_id')->count('peserta_id') : 0;
        $sudahPostTest = $tesPost ? Percobaan::where('tes_id', $tesPost->id)->distinct('peserta_id')->count('peserta_id') : 0;
        $sudahSurvey = $tesSurvey ? Percobaan::where('tes_id', $tesSurvey->id)->distinct('peserta_id')->count('peserta_id') : 0;

        return [
            'datasets' => [
                [
                    'label' => 'Peserta Sudah Mengerjakan',
                    'data' => [$sudahPreTest, $sudahPostTest, $sudahSurvey],
                    'backgroundColor' => ['#36A2EB', '#FFCE56', '#4BC0C0'],
                ],
            ],
            'labels' => ['Pre-Test', 'Post-Test', 'Survey Monev'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
