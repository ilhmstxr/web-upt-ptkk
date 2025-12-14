<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPelatihan extends ViewRecord
{
    use \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\Concerns\BuildsLikertData;

    protected static string $resource = PelatihanResource::class;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.pages.view-pelatihan';

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return $this->record->nama_pelatihan;
    }

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('index') => 'Manajemen Pelatihan',
            '#' => \Illuminate\Support\Str::limit($this->record->nama_pelatihan, 40),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ActionGroup::make([
                \Filament\Actions\Action::make('export_rekap')
                    ->label('Rekap Peserta (PDF)')
                    ->icon('heroicon-o-document-text')
                    ->url(fn() => route('export.template.rekap-pelatihan', ['pelatihanId' => $this->record->id]))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('export_excel')
                    ->label('Peserta (Excel)')
                    ->icon('heroicon-o-table-cells')
                    ->url(fn() => route('export.template.peserta-excel', ['pelatihanId' => $this->record->id]))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('export_instruktur')
                    ->label('Daftar Instruktur (PDF)')
                    ->icon('heroicon-o-users')
                    ->url(fn() => route('export.template.daftar-instruktur', ['pelatihanId' => $this->record->id]))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('export_biodata')
                    ->label('Biodata Peserta (PDF)')
                    ->icon('heroicon-o-identification')
                    ->url(fn() => route('export.template.biodata-peserta', ['pelatihanId' => $this->record->id]))
                    ->openUrlInNewTab(),
            ])
                ->label('Export Data')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray'),

            \Filament\Actions\EditAction::make()
                ->label('Edit Pelatihan'),
        ];
    }

    public function getSubheading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return new \Illuminate\Support\HtmlString(\Illuminate\Support\Facades\Blade::render(<<<'BLADE'
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
                    {{ \Carbon\Carbon::parse($record->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($record->tanggal_selesai)->format('d M Y') }}
                </div>
                
                <span class="text-gray-300 dark:text-gray-600">|</span>
                
                <div class="flex items-center gap-1">
                    <x-heroicon-o-users class="w-4 h-4" /> 
                    Total Peserta: {{ $record->pendaftaranPelatihan()->count() }}
                </div>
            </div>
        BLADE, ['record' => $this->record]));
    }

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
                    ->options(\App\Models\Instruktur::query()->pluck('nama', 'id'))
                    ->searchable()
                    ->multiple(),
            ])
            ->action(function (array $data) {
                $instructors = $data['instruktur_id'] ?? [];

                // Cek apakah sudah ada data KompetensiPelatihan untuk kompetensi ini di pelatihan ini
                $kompetensiPelatihan = $this->record->kompetensiPelatihan()
                    ->where('kompetensi_id', $data['kompetensi_id'])
                    ->first();

                if (!$kompetensiPelatihan) {
                    // Jika belum ada, buat baru
                    $createData = [
                        'kompetensi_id' => $data['kompetensi_id'],
                        // 'lokasi' => ... (jika ada input lokasi di form, tambahkan di sini)
                    ];
                    $kompetensiPelatihan = $this->record->kompetensiPelatihan()->create($createData);
                }

                if (!empty($instructors)) {
                    // Gunakan syncWithoutDetaching agar instruktur lama tidak hilang jika record sudah ada
                    $kompetensiPelatihan->instrukturs()->syncWithoutDetaching($instructors);
                }

                \Filament\Notifications\Notification::make()
                    ->title('Instruktur berhasil ditambahkan')
                    ->success()
                    ->send();
            });
    }

    public function getEvaluationData(): array
    {
        $pelatihanId = $this->record->id;

        // 1. Calculate Pretest & Posttest from Percobaan (more reliable than PendaftaranPelatihan)
        // Note: Using 'avg' on 'skor' column from Percobaan.
        // Assuming Percobaan stores score 0-100.
        $avgPretest = \App\Models\Percobaan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->whereHas('tes', fn($q) => $q->where('tipe', 'pre_test'))
            ->avg('skor') ?? 0;

        $avgPosttest = \App\Models\Percobaan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->whereHas('tes', fn($q) => $q->where('tipe', 'post_test'))
            ->avg('skor') ?? 0;

        $improvement = 0;
        if ($avgPretest > 0) {
            $improvement = (($avgPosttest - $avgPretest) / $avgPretest) * 100;
        }

        // 2. Calculate CSAT (Survey) using BuildsLikertData logic
        // Aggregating all Likert answers from 'survei' tests for this training
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId, 'survei');

        $avgCsat = 0;
        $respondentsCount = 0;

        if ($pertanyaanIds->isNotEmpty()) {
            [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);

            // Normalized answers don't assume a user context, just raw answers
            $allAnswers = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);

            // Calculate average scale (1-4)
            // Filter valid scales
            $validScales = $allAnswers->pluck('skala')->filter(fn($s) => is_numeric($s) && $s > 0);

            if ($validScales->isNotEmpty()) {
                $avgCsat = $validScales->avg();
            }

            // Count distinct respondents
            $respondentsCount = \App\Models\Percobaan::query()
                ->where('pelatihan_id', $pelatihanId)
                ->whereHas('tes', fn($q) => $q->where('tipe', 'survei'))
                ->count();
        }

        // 3. Competency Details
        // Keeping PendaftaranPelatihan logic as fallback for now, as breaking it down by competency 
        // via Percobaan requires traversing complex relationships not fully verified yet.
        $competencyStats = [];
        $sessions = $this->record->kompetensiPelatihan;

        foreach ($sessions as $session) {
            $sessionRegistrations = \App\Models\PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('kompetensi_pelatihan_id', $session->id)
                ->get();

            if ($sessionRegistrations->isEmpty()) {
                $pre = 0;
                $post = 0;
                $sat = 0;
            } else {
                $pre = $sessionRegistrations->avg('nilai_pre_test') ?? 0;
                $post = $sessionRegistrations->avg('nilai_post_test') ?? 0;
                $sat = $sessionRegistrations->avg('nilai_survey') ?? 0;
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

        // Check if there are any raw survey answers
        $hasSurveyAnswers = \App\Models\JawabanUser::query()
            ->whereHas('percobaan.tes', fn($q) => $q->where('pelatihan_id', $pelatihanId))
            ->exists();

        // Also check if there are any Percobaan records for pre/post test
        $hasPrePost = \App\Models\Percobaan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->whereHas('tes', fn($q) => $q->whereIn('tipe', ['pre_test', 'post_test']))
            ->exists();

        return [
            'avgPretest' => number_format($avgPretest, 1),
            'avgPosttest' => number_format($avgPosttest, 1),
            'improvement' => number_format($improvement, 0) . '%',
            'csat' => number_format($avgCsat, 1),
            'respondents' => $respondentsCount,
            'competencies' => $competencyStats,
            'hasData' => ($hasSurveyAnswers || $hasPrePost),
        ];
    }
}
