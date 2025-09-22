<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\JawabanUser;
use Filament\Widgets\ChartWidget;

class SurveyChart extends ChartWidget
{
    protected static ?string $heading = 'Chart Kepuasan Peserta';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        /**
         * PENTING: Anda perlu menentukan ID Pertanyaan yang ingin di-chart.
         * Misalnya, jika pertanyaan "Bagaimana tingkat kepuasan Anda?"
         * memiliki ID = 1 di tabel 'pertanyaan', maka gunakan angka 1.
         */
        $pertanyaanKepuasanId = 1; // <--- UBAH SESUAI ID PERTANYAAN ANDA

        // Query ini akan menghitung berapa kali setiap opsi jawaban dipilih
        // untuk pertanyaan spesifik tersebut.
        $data = $data = JawabanUser::where('jawaban_user.pertanyaan_id', $pertanyaanKepuasanId)
            ->join('opsi_jawaban', 'jawaban_user.opsi_jawaban_id', '=', 'opsi_jawaban.id')
            ->select('opsi_jawaban.teks_opsi')
            ->get()
            ->countBy('teks_opsi'); // Menghitung jumlah untuk setiap teks_opsi

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Jawaban',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
