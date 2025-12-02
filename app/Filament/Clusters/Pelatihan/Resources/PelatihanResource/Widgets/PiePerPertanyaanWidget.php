<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\Concerns\BuildsLikertData;
use App\Models\Pertanyaan;
use Filament\Widgets\Widget;
use Livewire\Attributes\Reactive;
use Illuminate\Database\Eloquent\Model;

class PiePerPertanyaanWidget extends Widget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Distribusi Skala per Pertanyaan';
    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.widgets.pie-per-pertanyaan-widget';

    public ?Model $record = null;

    #[Reactive]
    public ?int $pelatihanId = null;
    protected static bool $isLazy = false; // penting
    public array $charts = [];

    public function mount(): void
    {
        $pelatihanId = $this->pelatihanId ?? ($this->record?->id ?? request()->integer('pelatihanId'));
        
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);

        if ($pertanyaanIds->isEmpty()) {
            $this->charts = [];
            return;
        }

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $rows = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);

        $answersByQuestion = [];
        foreach ($rows as $r) {
            $pid = (int) ($r['pertanyaan_id'] ?? 0);
            $scale = (int) ($r['skala'] ?? 0);

            if ($pid === 0 || $scale === 0) {
                continue;
            }

            $answersByQuestion[$pid][$scale] = ($answersByQuestion[$pid][$scale] ?? 0) + 1;
        }

        $questions = Pertanyaan::whereIn('id', $pertanyaanIds)
            ->where('tipe_jawaban', 'skala_likert')
            ->with([
                'opsiJawabans:id,pertanyaan_id,teks_opsi',
                'templates.opsiJawabans:id,pertanyaan_id,teks_opsi',
            ])
            ->orderBy('tes_id')
            ->orderBy('nomor')
            ->get(['id', 'tes_id', 'nomor', 'teks_pertanyaan'])
            ->values(); // reindex 0..N-1

        $this->charts = $questions->map(function (Pertanyaan $q, int $i) use ($answersByQuestion) {
            $labels = $q->opsiJawabans?->pluck('teks_opsi')->values()->all() ?? [];

            if (empty($labels) && isset($answersByQuestion[$q->id])) {
                $maxScale = (int) max(array_keys($answersByQuestion[$q->id]));
                $labels = array_map(fn(int $idx) => "Skala {$idx}", range(1, $maxScale));
            }

            $counts = [];
            $labelCount = count($labels);
            if ($labelCount > 0) {
                for ($idx = 1; $idx <= $labelCount; $idx++) {
                    $counts[] = $answersByQuestion[$q->id][$idx] ?? 0;
                }
            } else {
                $counts = array_values($answersByQuestion[$q->id] ?? []);
            }

            $total   = array_sum($counts);
            $percentages = $total > 0
                ? array_map(fn($c) => round(($c / $total) * 100, 1), $counts)
                : array_fill(0, count($counts), 0);

            $displayNo = $i + 1;

            return [
            // $data = [
                'question_id'    => $q->id,
                'question_label' => "Q{$displayNo}. " . $q->teks_pertanyaan,
                'labels'         => $labels,
                'data'           => $counts,
                'percentages'    => $percentages,
            ];
            // dd($data);
        })->values()->all();
    }
}
