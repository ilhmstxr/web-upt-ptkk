<?php
// app/Filament/Resources/JawabanSurveiResource/Widgets/PiePerPertanyaanWidget.php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\Pertanyaan;
use Filament\Widgets\Widget;

class PiePerPertanyaanWidget extends Widget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Distribusi Skala per Pertanyaan';
    protected int|string|array $columnSpan = 'full';

    protected static string $view = 'filament.resources.jawaban-surveis.pages.pie-per-pertanyaan-widget';

    public array $charts = [];

    public function mount(): void
    {
        $pelatihanId   = request()->integer('pelatihanId');
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

        $questions = Pertanyaan::whereIn('id', $pertanyaanIds)->where('tipe_jawaban', 'skala_likert')
            ->orderBy('tes_id')->orderBy('nomor')
            ->get(['id', 'nomor', 'teks_pertanyaan']);

        $labels = ['Tidak Memuaskan', 'Kurang Memuaskan', 'Cukup Memuaskan', 'Sangat Memuaskan'];

        $this->charts = $questions->map(function (Pertanyaan $q) use ($matrix, $labels) {
            $data = $matrix[$q->id] ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0];

            return [
                'question_id'    => $q->id,
                'question_label' => ($q->nomor ? "Q{$q->nomor}. " : "Q{$q->id}. ") . $q->teks_pertanyaan,
                'labels'         => $labels,
                'data'           => [ $data[1] ?? 0, $data[2] ?? 0, $data[3] ?? 0, $data[4] ?? 0 ],
            ];
        })->values()->all();
    }
}
