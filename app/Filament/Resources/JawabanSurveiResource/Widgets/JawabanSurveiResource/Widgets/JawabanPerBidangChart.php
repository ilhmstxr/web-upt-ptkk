<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\JawabanUser;
use App\Models\Pertanyaan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JawabanPerBidangChart extends ChartWidget
{
    protected static ?string $heading = 'Rekapitulasi Jawaban Survei per Bidang';
    protected int | string | array $columnSpan = 'full';
    public ?int $pelatihanId = null;
    public ?int $bidangId = null;

    protected function getData(): array
    {
        if (!$this->pelatihanId || !$this->bidangId) {
            return [];
        }

        // Query untuk mengambil data jawaban dari peserta dalam pelatihan dan bidang tertentu
        $jawabanData = JawabanUser::query()
            ->join('percobaan', 'jawaban_user.percobaan_id', '=', 'percobaan.id')
            ->join('peserta', 'percobaan.peserta_id', '=', 'peserta.id')
            ->where('peserta.pelatihan_id', $this->pelatihanId)
            ->where('peserta.bidang_id', $this->bidangId)
            // Pastikan ini adalah percobaan survei, bukan tes
            // ->where('percobaan.tes_id', ID_SURVEI_ANDA) 
            ->select('pertanyaan_id', DB::raw('count(*) as total'))
            ->groupBy('pertanyaan_id')
            ->get();

        $labels = Pertanyaan::whereIn('id', $jawabanData->pluck('pertanyaan_id'))->pluck('teks_pertanyaan');
        $data = $jawabanData->pluck('total');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Jawaban',
                    'data' => $data,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // atau 'pie', 'line', dll.
    }
}
