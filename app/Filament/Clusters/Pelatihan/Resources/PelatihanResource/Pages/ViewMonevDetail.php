<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use App\Models\KompetensiPelatihan;
use App\Models\JawabanUser;
use App\Models\Pelatihan;
use App\Models\Pertanyaan;
use App\Models\Tes;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ViewMonevDetail extends Page
{
    protected static string $resource = PelatihanResource::class;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.pages.view-monev-detail';

    public Pelatihan $record;
    public KompetensiPelatihan $kompetensiPelatihan;
    public $surveiData = [];

    public function mount(Pelatihan $record, $kompetensi_id): void
    {
        $this->record = $record;
        $this->kompetensiPelatihan = KompetensiPelatihan::findOrFail($kompetensi_id);
        
        $this->prepareSurveiData();
    }

    public function getTitle(): string | Htmlable
    {
        return 'Analisis Hasil Survei Monev - ' . ($this->kompetensiPelatihan->kompetensi->nama_kompetensi ?? 'Detail Kompetensi');
    }

    protected function prepareSurveiData()
    {
        // 1. Get Survei Tests
        $surveiTesIds = Tes::where('pelatihan_id', $this->record->id)
            ->where('kompetensi_id', $this->kompetensiPelatihan->kompetensi_id)
            ->where('tipe', 'survei')
            ->pluck('id');

        if ($surveiTesIds->isEmpty()) {
            return;
        }

        // 2. Get Questions
        $questions = Pertanyaan::whereIn('tes_id', $surveiTesIds)
            ->with(['opsiJawabans'])
            ->get();

        // 3. Get Answers
        $answers = JawabanUser::whereIn('pertanyaan_id', $questions->pluck('id'))
            ->with(['opsiJawaban'])
            ->get();

        // 4. Calculate Stats
        
        // Total Accumulation (IKM)
        // Assuming scale 1-4 or similar, mapped to 0-100
        // For simplicity, let's calculate average score based on 'nilai_jawaban' if available, or just count
        // If 'nilai_jawaban' is not reliable, we might need to map opsi_jawaban to score.
        // Let's assume 'nilai_jawaban' in JawabanUser is populated or we use OpsiJawaban 'nilai' (if exists) or just index.
        // Checking OpsiJawaban schema: it has 'apakah_benar', no explicit score. 
        // Checking JawabanUser schema: it has 'nilai_jawaban'. Let's use that.
        
        $totalScore = $answers->avg('nilai_jawaban') ?? 0; // Raw average
        // Normalize to 0-100 if the scale is different. 
        // If max score per question is 4, then (avg / 4) * 100.
        // Let's assume max score is 4 for now based on the reference HTML (Sangat Memuaskan etc).
        $ikm = ($totalScore / 4) * 100;

        // Distribution for Total Chart
        $distribution = [
            'Sangat Memuaskan' => 0,
            'Memuaskan' => 0,
            'Kurang Memuaskan' => 0,
            'Tidak Memuaskan' => 0,
        ];

        foreach ($answers as $ans) {
            // Map score to label roughly
            $val = $ans->nilai_jawaban;
            if ($val >= 4) $distribution['Sangat Memuaskan']++;
            elseif ($val >= 3) $distribution['Memuaskan']++;
            elseif ($val >= 2) $distribution['Kurang Memuaskan']++;
            else $distribution['Tidak Memuaskan']++;
        }
        
        // Per Category
        $categories = $questions->groupBy('kategori');
        $categoryStats = [];
        
        foreach ($categories as $catName => $catQuestions) {
            $catQIds = $catQuestions->pluck('id');
            $catAnswers = $answers->whereIn('pertanyaan_id', $catQIds);
            $catAvg = $catAnswers->avg('nilai_jawaban') ?? 0;
            
            $categoryStats[$catName ?? 'Lainnya'] = number_format($catAvg, 1);
        }

        // Per Question Detail
        $questionStats = [];
        foreach ($questions as $q) {
            $qAnswers = $answers->where('pertanyaan_id', $q->id);
            $qDist = [
                'Sangat Memuaskan' => 0,
                'Memuaskan' => 0,
                'Kurang Memuaskan' => 0,
                'Tidak Memuaskan' => 0,
            ];
            
            foreach ($qAnswers as $ans) {
                $val = $ans->nilai_jawaban;
                if ($val >= 4) $qDist['Sangat Memuaskan']++;
                elseif ($val >= 3) $qDist['Memuaskan']++;
                elseif ($val >= 2) $qDist['Kurang Memuaskan']++;
                else $qDist['Tidak Memuaskan']++;
            }
            
            $questionStats[] = [
                'id' => $q->id,
                'nomor' => $q->nomor,
                'teks' => $q->teks_pertanyaan,
                'kategori' => $q->kategori ?? 'Lainnya',
                'distribution' => array_values($qDist), // [val1, val2, val3, val4]
            ];
        }

        $this->surveiData = [
            'ikm' => number_format($ikm, 2),
            'ikm_category' => $ikm >= 80 ? 'SANGAT BAIK' : ($ikm >= 60 ? 'BAIK' : 'KURANG'),
            'total_distribution' => array_values($distribution),
            'category_stats' => $categoryStats,
            'question_stats' => collect($questionStats)->groupBy('kategori'),
        ];
    }
}
