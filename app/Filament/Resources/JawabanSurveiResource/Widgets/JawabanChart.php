<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\JawabanUser;
use App\Models\Pelatihan;
use Filament\Widgets\ChartWidget;

class JawabanChart extends ChartWidget
{
    // protected static ?string $heading = 'Chart';
    protected static ?string $heading = 'Distribusi Jawaban';
    public ?Pelatihan $pelatihan = null;

    protected function getData(): array
    {
        if (is_null($this->pelatihan)) {
            return [];
        }

        // Query yang benar sesuai diagram database Anda
        $data = JawabanUser::query()
            ->join('percobaans', 'jawaban_users.percobaan_id', '=', 'percobaans.id')
            ->join('peserta_surveis', 'percobaans.pesertaSurvei_id', '=', 'peserta_surveis.id')
            ->join('opsi_jawabans', 'jawaban_users.opsi_jawabans_id', '=', 'opsi_jawabans.id')
            ->where('peserta_surveis.pelatihan_id', $this->pelatihan->id)
            // ▼▼▼ TAMBAHKAN FILTER INI ▼▼▼
            // ->where('jawaban_users.pertanyaan_id', $pertanyaanId)
            ->selectRaw('opsi_jawabans.teks_opsi, count(*) as total')
            ->groupBy('opsi_jawabans.teks_opsi')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Jawaban',
                    'data' => $data->pluck('total')->toArray(),
                    // Opsional: tambahkan warna untuk chart doughnut/pie
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                ],
            ],
            'labels' => $data->pluck('teks_opsi')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
