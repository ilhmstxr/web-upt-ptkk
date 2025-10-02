<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\Pertanyaan;
use Filament\Widgets\Widget;
use Livewire\Attributes\Reactive;

// Pastikan Anda telah meng-import trait BuildsLikertData jika ada di direktori lain
// use App\Filament\Resources\JawabanSurveiResource\Widgets\Concerns\BuildsLikertData;

class PiePerPertanyaanWidget extends Widget
{
    // Jika Anda menggunakan trait, pastikan baris ini ada dan path-nya benar
    use BuildsLikertData;

    protected static ?string $heading = 'Distribusi Skala per Pertanyaan';
    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.resources.jawaban-surveis.pages.pie-per-pertanyaan-widget';

    #[Reactive]
    public ?int $pelatihanId = null;
    protected static bool $isLazy = false; // penting
    public array $charts = [];

    public function mount(): void
    {
        $pelatihanId = $this->pelatihanId ?? request()->integer('pelatihanId');
        // Asumsi trait 'BuildsLikertData' menyediakan method-method berikut.
        // Jika tidak, pastikan method ini ada di dalam class ini.
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);

        if ($pertanyaanIds->isEmpty()) {
            $this->charts = [];
            return;
        }

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $rows = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);

        $matrix = [];
        foreach ($pertanyaanIds as $pid) {
            $matrix[$pid] = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        }
        foreach ($rows as $r) {
            if (!empty($r['skala'])) {
                $matrix[(int) $r['pertanyaan_id']][(int) $r['skala']]++;
            }
        }
        
        $labels = ['Tidak Memuaskan', 'Kurang Memuaskan', 'Cukup Memuaskan', 'Sangat Memuaskan'];

        // $questions = Pertanyaan::whereIn('id', $pertanyaanIds)->where('tipe_jawaban', 'skala_likert')
        //     ->orderBy('tes_id')->orderBy('nomor')
        //     ->get(['id', 'nomor', 'teks_pertanyaan']);


        // $this->charts = $questions->map(function (Pertanyaan $q) use ($matrix, $labels) {
        //     $data = $matrix[$q->id] ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        //     $counts = [$data[1] ?? 0, $data[2] ?? 0, $data[3] ?? 0, $data[4] ?? 0];
        //     $total = array_sum($counts);

        //     $percentages = $total > 0
        //         ? array_map(fn($count) => round(($count / $total) * 100, 1), $counts)
        //         : [0, 0, 0, 0];

        //     return [
        //         'question_id'    => $q->id,
        //         'question_label' => ($q->nomor ? "Q{$q->nomor}. " : "Q{$q->id}. ") . $q->teks_pertanyaan,
        //         'labels'         => $labels,
        //         'data'           => $counts,       // Data jumlah/total
        //         'percentages'    => $percentages,  // Data persentase
        //     ];

        $questions = Pertanyaan::whereIn('id', $pertanyaanIds)
            ->where('tipe_jawaban', 'skala_likert')
            ->orderBy('tes_id')->orderBy('nomor')
            ->get(['id', 'nomor', 'teks_pertanyaan'])
            ->values(); // reindex 0..N-1

        $this->charts = $questions->map(function (Pertanyaan $q, int $i) use ($matrix, $labels) {
            $data  = $matrix[$q->id] ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $counts = [$data[1] ?? 0, $data[2] ?? 0, $data[3] ?? 0, $data[4] ?? 0];
            $total  = array_sum($counts);
            $percentages = $total > 0
                ? array_map(fn($c) => round(($c / $total) * 100, 1), $counts)
                : [0, 0, 0, 0];

            $displayNo = $i + 1;

            return [
                'question_id'    => $q->id,
                'question_label' => "Q{$displayNo}. " . $q->teks_pertanyaan,
                'labels'         => $labels,
                'data'           => $counts,
                'percentages'    => $percentages,
            ];
        })->values()->all();
    }
}
