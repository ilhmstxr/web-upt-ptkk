<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\Pertanyaan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JawabanPerKategoriChart extends ChartWidget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Distribusi Skala per Kategori';
    protected int|string|array $columnSpan = 'full';

    private array $arrayCustom = [
        'Pendapat Tentang Penyelenggaran Pelatihan',
        'Persepsi Terhadap Program Pelatihan',
        'Penilaian Terhadap Instruktur',
    ];

    protected function getData(): array
    {
        $pelatihanId   = request()->integer('pelatihanId');
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);
        if ($pertanyaanIds->isEmpty()) return ['labels'=>[],'datasets'=>[]];

        [$pivot,$opsiIdToSkala,$opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $rows = $this->normalizedAnswers($pelatihanId,$pertanyaanIds,$pivot,$opsiIdToSkala,$opsiTextToId);

        $tesIds = DB::table('percobaan as pr')
            ->join('tes as t','t.id','=','pr.tes_id')
            ->when($pelatihanId, fn($q)=>$q->where('t.pelatihan_id',$pelatihanId))
            ->pluck('t.id')->unique()->values();

        $pertanyaanAll = Pertanyaan::whereIn('tes_id',$tesIds)
            ->orderBy('tes_id')->orderBy('nomor')
            ->get(['id','tes_id','tipe_jawaban','teks_pertanyaan','nomor']);

        $mapKategori = [];
        foreach ($pertanyaanAll->groupBy('tes_id') as $questions) {
            $groupKey=1; $temp=[];
            foreach ($questions as $q) {
                $temp[]=$q;
                $isBoundary = $q->tipe_jawaban==='teks_bebas'
                    && str_starts_with(strtolower(trim($q->teks_pertanyaan)),'pesan dan kesan');
                if ($isBoundary) {
                    $category = $this->arrayCustom[$groupKey-1] ?? ('Kategori '.$groupKey);
                    foreach ($temp as $item)
                        if ($item->tipe_jawaban==='skala_likert') $mapKategori[$item->id]=$category;
                    $temp=[]; $groupKey++;
                }
            }
            if (!empty($temp)) {
                $category = $this->arrayCustom[$groupKey-1] ?? ('Kategori '.$groupKey);
                foreach ($temp as $item)
                    if ($item->tipe_jawaban==='skala_likert') $mapKategori[$item->id]=$category;
            }
        }

        $matrix = [];
        foreach ($rows as $r) {
            $cat = $mapKategori[$r['pertanyaan_id']] ?? 'Tanpa Kategori';
            $matrix[$cat] = $matrix[$cat] ?? [1=>0,2=>0,3=>0,4=>0];
            if (!empty($r['skala'])) $matrix[$cat][(int)$r['skala']]++;
        }

        $labels = array_keys($matrix);
        $d1=$d2=$d3=$d4=[];
        foreach ($labels as $cat) {
            $d1[]=$matrix[$cat][1] ?? 0;
            $d2[]=$matrix[$cat][2] ?? 0;
            $d3[]=$matrix[$cat][3] ?? 0;
            $d4[]=$matrix[$cat][4] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label'=>'Skala 1','data'=>$d1],
                ['label'=>'Skala 2','data'=>$d2],
                ['label'=>'Skala 3','data'=>$d3],
                ['label'=>'Skala 4','data'=>$d4],
            ],
            'options' => ['scales'=>['x'=>['stacked'=>true],'y'=>['stacked'=>true]]],
        ];
    }

    protected function getType(): string { return 'bar'; }
}
