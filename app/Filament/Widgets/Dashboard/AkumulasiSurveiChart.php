<?php

namespace App\Filament\Widgets;

use App\Models\OpsiJawaban; // Menggunakan model OpsiJawaban sebagai sumber
use Filament\Widgets\ChartWidget;

class AkumulasiSurveiChart extends ChartWidget
{
    protected static ?string $heading = 'Akumulasi Survei Peserta';
    protected static ?int $sort = 3; // Urutan widget

    // Mengatur lebar widget agar menempati 1 kolom
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        // Asumsi: Model OpsiJawaban ada dan memiliki relasi 'jawabanUsers'
        // Asumsi: OpsiJawaban diurutkan berdasarkan ID (1: Kurang, 2: Cukup, dst.)
        $data = OpsiJawaban::query()
            // Mengambil opsi jawaban yang relevan untuk survei kepuasan
            // ->where('pertanyaan_id', 1) // Sesuaikan jika perlu filter
            ->withCount('jawabanUsers') // Menghitung relasi menggunakan Eloquent
            ->orderBy('id', 'asc') // Mengurutkan berdasarkan ID
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Jawaban',
                    'data' => $data->pluck('jawaban_users_count')->all(),
                    'backgroundColor' => [
                        '#F87171', // Merah untuk 'Kurang'
                        '#FBBF24', // Kuning untuk 'Cukup'
                        '#60A5FA', // Biru untuk 'Puas'
                        '#34D399', // Hijau untuk 'Sangat Puas'
                    ],
                ],
            ],
            'labels' => $data->pluck('teks_opsi')->all(),
        ];
    }

    protected function getType(): string
    {
        // Mengubah tipe chart menjadi 'bar' sesuai sketsa
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y', // Membuat bar chart menjadi horizontal
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
