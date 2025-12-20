<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\DB;
use App\Models\JawabanUser;
use App\Models\OpsiJawaban;
use App\Models\Pertanyaan;
use App\Models\Tes;

class ViewPelatihan extends ViewRecord
{
    use \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\Concerns\BuildsLikertData;

    protected static string $resource = PelatihanResource::class;

    protected static string $view =
    'filament.clusters.pelatihan.resources.pelatihan-resource.pages.view-pelatihan';

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $this->record->nama_pelatihan;
    }

    public function getBreadcrumbs(): array
    {
        return [
            PelatihanResource::getUrl('index') => 'Manajemen Pelatihan',
            '#' => \Illuminate\Support\Str::limit($this->record->nama_pelatihan, 40),
        ];
    }

    /**
     * ======================
     * HEADER ACTIONS
     * ======================
     */
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ActionGroup::make([
                \Filament\Actions\Action::make('export_rekap')
                    ->label('Rekap Peserta (PDF)')
                    ->icon('heroicon-o-document-text')
                    ->url(fn() => route('export.template.rekap-pelatihan', [
                        'pelatihanId' => $this->record->id,
                    ]))
                    ->openUrlInNewTab(),

                \Filament\Actions\Action::make('export_excel')
                    ->label('Peserta (Excel)')
                    ->icon('heroicon-o-table-cells')
                    ->url(fn() => route('export.template.peserta-excel', [
                        'pelatihanId' => $this->record->id,
                    ]))
                    ->openUrlInNewTab(),

                \Filament\Actions\Action::make('export_instruktur')
                    ->label('Daftar Instruktur (PDF)')
                    ->icon('heroicon-o-users')
                    ->url(fn() => route('export.template.daftar-instruktur', [
                        'pelatihanId' => $this->record->id,
                    ]))
                    ->openUrlInNewTab(),

                \Filament\Actions\Action::make('export_biodata')
                    ->label('Biodata Peserta (PDF)')
                    ->icon('heroicon-o-identification')
                    ->url(fn() => route('export.template.biodata-peserta', [
                        'pelatihanId' => $this->record->id,
                    ]))
                    ->openUrlInNewTab(),
            ])
                ->label('Export Data')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray'),

            \Filament\Actions\EditAction::make()
                ->label('Edit Pelatihan'),
        ];
    }

    public function getSubheading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return new HtmlString(
            Blade::render(<<<'BLADE'
                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ match($record->status) {
                        'aktif' => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800',
                        'belum dimulai' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                        'selesai' => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600',
                        default => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600'
                    } }} border">
                        <span class="w-1.5 h-1.5 {{ match($record->status) {
                            'aktif' => 'bg-green-500',
                            'belum dimulai' => 'bg-blue-500',
                            'selesai' => 'bg-gray-500',
                            default => 'bg-gray-500'
                        } }} rounded-full mr-1.5 animate-pulse"></span>
                        {{ ucfirst($record->status) }}
                    </span>

                    <div class="flex items-center gap-1">
                        <x-heroicon-o-calendar class="w-4 h-4" />
                        {{ \Carbon\Carbon::parse($record->tanggal_mulai)->format('d M') }}
                        -
                        {{ \Carbon\Carbon::parse($record->tanggal_selesai)->format('d M Y') }}
                    </div>

                    <span class="text-gray-300 dark:text-gray-600">|</span>

                    <div class="flex items-center gap-1">
                        <x-heroicon-o-users class="w-4 h-4" />
                        Total Peserta: {{ $record->pendaftaranPelatihan()->count() }}
                    </div>
                </div>
            BLADE, ['record' => $this->record])
        );
    }

    /**
     * ======================
     * ADD INSTRUCTOR ACTION
     * ======================
     */
    public function addInstructorAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('addInstructor')
            ->label('Tambah Instruktur')
            ->icon('heroicon-o-plus')
            ->color('primary')
            ->form([
                \Filament\Forms\Components\Select::make('kompetensi_id')
                    ->label('Kompetensi')
                    ->options(\App\Models\Kompetensi::pluck('nama_kompetensi', 'id'))
                    ->searchable()
                    ->required(),

                \Filament\Forms\Components\Select::make('instruktur_id')
                    ->label('Pilih Instruktur (Opsional)')
                    ->options(\App\Models\Instruktur::pluck('nama', 'id'))
                    ->searchable()
                    ->multiple(),
            ])
            ->action(function (array $data) {
                $kompetensiPelatihan = $this->record->kompetensiPelatihan()
                    ->firstOrCreate(['kompetensi_id' => $data['kompetensi_id']]);

                if (!empty($data['instruktur_id'])) {
                    $kompetensiPelatihan
                        ->instrukturs()
                        ->syncWithoutDetaching($data['instruktur_id']);
                }

                \Filament\Notifications\Notification::make()
                    ->title('Instruktur berhasil ditambahkan')
                    ->success()
                    ->send();
            });
    }

    /**
     * ======================
     * ADD COMPETENCY ACTION (NEW)
     * ======================
     */
    public function addCompetencyAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('addCompetency')
            ->label('Tambah Kompetensi')
            ->icon('heroicon-o-plus-circle')
            ->color('primary')
            ->form([
                \Filament\Forms\Components\Select::make('kompetensi_id')
                    ->label('Kompetensi')
                    ->options(\App\Models\Kompetensi::pluck('nama_kompetensi', 'id'))
                    ->searchable()
                    ->required()
                    ->helperText('Pilih materi/kompetensi yang akan ditambahkan.'),

                \Filament\Forms\Components\Select::make('instruktur_id')
                    ->label('Instruktur / Mentor')
                    ->options(\App\Models\Instruktur::pluck('nama', 'id'))
                    ->searchable()
                    ->multiple()
                    ->required(),

                \Filament\Forms\Components\TextInput::make('lokasi')
                    ->label('Lokasi / Ruangan')
                    ->default($this->record->lokasi ?? 'UPT-PTKK')
                    ->required(),
            ])
            ->action(function (array $data) {
                // 1. Create Competency Pivot (Allow Duplicates)
                $kompetensiPelatihan = $this->record->kompetensiPelatihan()
                    ->create([
                        'kompetensi_id' => $data['kompetensi_id'],
                        'lokasi' => $data['lokasi']
                    ]);

                // 2. Sync Instructors
                if (!empty($data['instruktur_id'])) {
                    $kompetensiPelatihan->instrukturs()->sync($data['instruktur_id']);
                }

                \Filament\Notifications\Notification::make()
                    ->title('Kompetensi berhasil ditambahkan')
                    ->success()
                    ->send();
            });
    }

    /**
     * ======================
     * EVALUATION DATA (LIKERT, PRE, POST)
     * ======================
     */
    public function getEvaluationData(): array
    {
        $pelatihanId = $this->record->id;

        // 1. Calculate Pretest & Posttest (Existing Logic is fine for scores)
        $avgPretest = \App\Models\PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)->avg('nilai_pre_test') ?? 0;
        $avgPosttest = \App\Models\PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)->avg('nilai_post_test') ?? 0;

        $improvement = 0;
        if ($avgPretest > 0) {
            $improvement = (($avgPosttest - $avgPretest) / $avgPretest) * 100;
        }

        // 2. Calculate CSAT (Survey) - UPGRADE TO LIKERT LOGIC
        // Instead of simple avg('nilai_survey'), we use the buildsLikert logic basics
        // Or we keep simple avg if 'nilai_survey' is reliable. The user request implied using BuildsLikertData logic.
        // Let's assume 'nilai_survey' column in PendaftaranPelatihan IS the aggregate cache.
        // If it's 0, we might need to calc from JawabanUser.
        // BUT, for the "Main View", let's stick to the column if possible for performance, 
        // OR recalc if the user insists on "BuildsLikertData" logic.
        // User said: "seharusbya ini muncul diagramnya, berdasarkan skala likert yang ada di opsi jawabn"
        // This likely refers to the Detail view mostly, but let's check if the Main view relies on it.
        // Main view uses $evalData['csat'] and $evalData['total_chart'] etc? 
        // The blade `view-pelatihan.blade.php` uses `csat` (number) and charts.
        // Wait, `view-pelatihan.blade.php` has code for `chartTotalAccumulation` and `chartCategories`??
        // Let's check `view-pelatihan.blade.php` content again from history.
        // No, `view-pelatihan.blade.php` earlier showed basic stats in `getEvaluationData`.
        // There were NO charts in `view-pelatihan.blade.php` except the simple Pre/Post bar and CSAT star rating.
        // AND table "Rincian Nilai per Kompetensi".
        // The "Charts" (Total Distribution, Categories, Pie) were in `ViewMonevDetail`.

        // So for `ViewPelatihan`, we just need to ensure `csat` and `kepuasan` per competency are correct.
        // If `nilai_survey` in PendaftaranPelatihan is empty/null, we should calculate it from JawabanUser.

        // CALCULATE GLOBAL CSAT FROM JAWABAN USER
        $surveiTesIds = \App\Models\Tes::where('pelatihan_id', $pelatihanId)
            ->where('tipe', 'survei')
            ->pluck('id');

        $pertanyaanIds = \App\Models\Pertanyaan::whereIn('tes_id', $surveiTesIds)
            ->where('tipe_jawaban', 'skala_likert')
            ->pluck('id');

        $avgCsat = 0;
        $respondentsCount = 0;

        if ($pertanyaanIds->isNotEmpty()) {
            // Build Maps
            [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
            $allAnswers = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId); // No competency filter for global

            $avgScale = $allAnswers->avg('skala') ?? 0;
            $avgCsat = ($avgScale / 4) * 100;

            // Count unique respondents (percobaan_id or user_id? Answer table has percobaan_id)
            // normalizedAnswers returns mapped collection. We need access to original distinct users?
            // normalizedAnswers loses user context.
            // Let's count from distinct percobaan_id in range.
            $respondentsCount = \App\Models\JawabanUser::whereIn('pertanyaan_id', $pertanyaanIds)
                ->join('percobaan', 'percobaan.id', '=', 'jawaban_user.percobaan_id')
                ->join('tes', 'tes.id', '=', 'percobaan.tes_id')
                ->where('tes.pelatihan_id', $pelatihanId)
                ->distinct('percobaan.peserta_id')
                ->count('percobaan.peserta_id');
        }

        // --- NEW: Calculate Chart Data from $allAnswers ---
        $totalCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $categoryCounts = [];
        $questionCounts = [];

        // Fetch Question metadata (text, category)
        $allPertanyaan = collect();
        if ($pertanyaanIds->isNotEmpty()) {
            $allPertanyaan = \App\Models\Pertanyaan::whereIn('id', $pertanyaanIds)->get()->keyBy('id');
        }

        if (isset($allAnswers) && $allAnswers->isNotEmpty()) {
            foreach ($allAnswers as $ans) {
                $pid = $ans['pertanyaan_id'];
                $skala = $ans['skala'];

                if (!$skala || $skala < 1 || $skala > 4) continue;

                $p = $allPertanyaan->get($pid);
                // Default 'Umum' if category is null/empty
                $kategori = $p && !empty($p->kategori) ? $p->kategori : 'Umum';

                // 1. Total Distribution
                if (isset($totalCounts[$skala])) $totalCounts[$skala]++;

                // 2. Category Distribution
                if (!isset($categoryCounts[$kategori])) {
                    $categoryCounts[$kategori] = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                }
                $categoryCounts[$kategori][$skala]++;

                // 3. Question Breakdown
                if (!isset($questionCounts[$pid])) {
                    $questionCounts[$pid] = [
                        'id' => $pid,
                        'teks' => $p->teks ?? 'Pertanyaan #' . $pid,
                        'kategori' => $kategori,
                        'counts' => [1 => 0, 2 => 0, 3 => 0, 4 => 0]
                    ];
                }
                $questionCounts[$pid]['counts'][$skala]++;
            }
        }

        // Format for Chart.js (Frontend will consume this)
        // Chart 1: Distribution Total
        $totalChart = [
            'labels' => ['Tidak Puas', 'Kurang Puas', 'Cukup Puas', 'Sangat Puas'],
            'datasets' => [[
                'data' => [$totalCounts[1], $totalCounts[2], $totalCounts[3], $totalCounts[4]],
                'backgroundColor' => ['#ef4444', '#f97316', '#3b82f6', '#22c55e']
            ]]
        ];

        // Chart 2: Stacked Bar Per Category
        $catLabels = array_keys($categoryCounts);
        $dataset1 = []; // Tidak Puas
        $dataset2 = []; // Kurang Puas
        $dataset3 = []; // Cukup Puas
        $dataset4 = []; // Sangat Puas

        foreach ($catLabels as $cat) {
            $dataset1[] = $categoryCounts[$cat][1];
            $dataset2[] = $categoryCounts[$cat][2];
            $dataset3[] = $categoryCounts[$cat][3];
            $dataset4[] = $categoryCounts[$cat][4];
        }

        $categoryChart = [
            'labels' => $catLabels,
            'datasets' => [
                ['label' => 'Tidak Puas', 'data' => $dataset1, 'backgroundColor' => '#ef4444'],
                ['label' => 'Kurang Puas', 'data' => $dataset2, 'backgroundColor' => '#f97316'],
                ['label' => 'Cukup Puas', 'data' => $dataset3, 'backgroundColor' => '#3b82f6'],
                ['label' => 'Sangat Puas', 'data' => $dataset4, 'backgroundColor' => '#22c55e'],
            ]
        ];

        // Chart 3: Grouped Questions
        $groupedQuestions = collect($questionCounts)->groupBy('kategori')->map(function ($group) {
            return $group->map(function ($q) {
                $q['total_responden'] = array_sum($q['counts']);
                return $q;
            })->values();
        })->toArray();

        // 3. Competency Details
        $competencyStats = [];
        $sessions = $this->record->kompetensiPelatihan;

        foreach ($sessions as $session) {
            $kompetensiId = $session->kompetensi_id;
            $kompetensiPelatihanId = $session->id; // This is the ID in pivot table usually? Or kompetensi_pelatihan table id.

            // Pre/Post can stay from PendaftaranPelatihan
            $pre = \App\Models\PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('kompetensi_pelatihan_id', $kompetensiPelatihanId) // Use correct column
                ->avg('nilai_pre_test') ?? 0;

            $post = \App\Models\PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('kompetensi_pelatihan_id', $kompetensiPelatihanId)
                ->avg('nilai_post_test') ?? 0;

            // Calculate Satisfaction per Competency using LIKERT Logic
            $compPertanyaanIds = $this->collectPertanyaanIds($pelatihanId, $kompetensiPelatihanId);

            // FALLBACK: If specific competency survey is empty, use generic
            if ($compPertanyaanIds->isEmpty()) {
                $compPertanyaanIds = $this->collectPertanyaanIds($pelatihanId, null);
            }

            $sat = 0;
            if ($compPertanyaanIds->isNotEmpty()) {
                [$cPivot, $cOpsiIdToSkala, $cOpsiTextToId] = $this->buildLikertMaps($compPertanyaanIds);
                $cAnswers = $this->normalizedAnswers($pelatihanId, $compPertanyaanIds, $cPivot, $cOpsiIdToSkala, $cOpsiTextToId, $kompetensiPelatihanId);
                $cAvgScale = $cAnswers->avg('skala') ?? 0;
                $sat = ($cAvgScale / 4) * 100;
            }

            $competencyStats[] = [
                'name' => $session->kompetensi->nama_kompetensi ?? 'Unknown',
                'pretest' => number_format($pre, 1),
                'posttest' => number_format($post, 1),
                'kepuasan' => number_format($sat, 1),
                'status' => $post >= 85 ? 'Sangat Baik' : ($post >= 75 ? 'Baik' : 'Cukup'),
                'status_color' => $post >= 85 ? 'success' : ($post >= 75 ? 'info' : 'warning'),
            ];
        }

        // Check data existence
        $hasData = ($respondentsCount > 0) || ($avgPretest > 0) || ($avgPosttest > 0);

        return [
            'avgPretest' => number_format($avgPretest, 1),
            'avgPosttest' => number_format($avgPosttest, 1),
            'improvement' => number_format($improvement, 0) . '%',
            'csat' => number_format($avgCsat, 1),
            'respondents' => $respondentsCount,
            'competencies' => $competencyStats,
            'hasData' => $hasData,
            'total_chart' => $totalChart,
            'category_chart' => $categoryChart,
            'question_stats' => $groupedQuestions,
        ];
    }
    // =========================================================================
    // OVERRIDE/CUSTOM LIKERT LOGIC (Supports Competency Filter)
    // =========================================================================

    protected function collectPertanyaanIds(?int $pelatihanId, ?int $kompetensiPelatihanId = null): \Illuminate\Support\Collection
    {
        return JawabanUser::query()
            ->from('jawaban_user as ju')
            ->join('percobaan as pr', 'pr.id', '=', 'ju.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->join('pertanyaan as p', 'p.id', '=', 'ju.pertanyaan_id')
            ->where('t.tipe', 'survei')
            ->where('p.tipe_jawaban', 'skala_likert')
            ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
            ->when($kompetensiPelatihanId, fn($q) => $q->where('t.kompetensi_pelatihan_id', $kompetensiPelatihanId))
            ->distinct()
            ->pluck('ju.pertanyaan_id')
            ->values();
    }

    protected function normalizePertanyaanIds(mixed $input): \Illuminate\Support\Collection
    {
        return collect($input)
            ->flatten()
            ->map(fn($v) => is_numeric($v) ? (int) $v : $v)
            ->unique()
            ->values();
    }

    protected function buildLikertMaps($pertanyaanIds): array
    {
        $ids = $this->normalizePertanyaanIds($pertanyaanIds);
        if ($ids->isEmpty())
            return [collect(), collect(), collect()];

        $pivot = DB::table('pivot_jawaban')
            ->whereIn('pertanyaan_id', $ids->all())
            ->pluck('template_pertanyaan_id', 'pertanyaan_id');

        $opsi = OpsiJawaban::whereIn(
            'pertanyaan_id',
            $ids->merge($pivot->values())->unique()->all()
        )->orderBy('id')->get();

        $opsiIdToSkala = $opsi->groupBy('pertanyaan_id')->map(function ($rows) {
            $map = [];
            foreach ($rows->pluck('id')->values() as $i => $id) {
                $map[$id] = $i + 1;
            }
            return $map;
        });

        $opsiTextToId = $opsi->groupBy('pertanyaan_id')
            ->map(fn($rows) => $rows->pluck('id', 'teks_opsi')->mapWithKeys(
                fn($id, $teks) => [trim($teks) => $id]
            ));

        return [$pivot, $opsiIdToSkala, $opsiTextToId];
    }

    protected function normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId, $kompetensiPelatihanId = null)
    {
        $ids = $this->normalizePertanyaanIds($pertanyaanIds);
        if ($ids->isEmpty())
            return collect();

        $jawaban = JawabanUser::query()
            ->join('percobaan as pr', 'pr.id', '=', 'jawaban_user.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->whereIn('jawaban_user.pertanyaan_id', $ids->all())
            ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
            ->when($kompetensiPelatihanId, fn($q) => $q->where('t.kompetensi_pelatihan_id', $kompetensiPelatihanId))
            ->select([
                'jawaban_user.pertanyaan_id',
                'jawaban_user.opsi_jawaban_id',
                'jawaban_user.jawaban_teks',
            ])
            ->get();

        return $jawaban->map(function ($j) use ($pivot, $opsiIdToSkala, $opsiTextToId) {
            $pid = (int) $j->pertanyaan_id;
            $source = $opsiIdToSkala->get($pid) ? $pid : ($pivot[$pid] ?? $pid);

            $opsiId = $j->opsi_jawaban_id;
            if (!$opsiId && $j->jawaban_teks) {
                $opsiId = optional($opsiTextToId->get($source))->get(trim((string) $j->jawaban_teks));
            }

            $skalaMap = $opsiIdToSkala->get($source, []);
            $skala = $opsiId ? ($skalaMap[$opsiId] ?? null) : null;
            if ($skala !== null) {
                $maxScale = count($skalaMap);
                $skala = $maxScale > 0 ? max(1, min($maxScale, (int) $skala)) : (int) $skala;
            }

            return [
                'pertanyaan_id' => $pid,
                'skala' => $skala,
            ];
        });
    }
}
