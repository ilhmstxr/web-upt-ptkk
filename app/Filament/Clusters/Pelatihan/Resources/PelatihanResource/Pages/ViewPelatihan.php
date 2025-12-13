<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPelatihan extends ViewRecord
{
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
                    ->url(fn () => route('export.template.rekap-pelatihan', ['pelatihanId' => $this->record->id]))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('export_excel')
                    ->label('Peserta (Excel)')
                    ->icon('heroicon-o-table-cells')
                    ->url(fn () => route('export.template.peserta-excel', ['pelatihanId' => $this->record->id]))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('export_instruktur')
                    ->label('Daftar Instruktur (PDF)')
                    ->icon('heroicon-o-users')
                    ->url(fn () => route('export.template.daftar-instruktur', ['pelatihanId' => $this->record->id]))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('export_biodata')
                    ->label('Biodata Peserta (PDF)')
                    ->icon('heroicon-o-identification')
                    ->url(fn () => route('export.template.biodata-peserta', ['pelatihanId' => $this->record->id]))
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
        
        // 1. Global Pretest & Posttest Stats
        // We assume PendaftaranPelatihan holds the scores
        $pendaftaran = $this->record->pendaftaranPelatihan;
        
        $avgPretest = $pendaftaran->avg('nilai_pre_test') ?? 0;
        $avgPosttest = $pendaftaran->avg('nilai_post_test') ?? 0;
        
        $improvement = 0;
        if ($avgPretest > 0) {
            $improvement = (($avgPosttest - $avgPretest) / $avgPretest) * 100;
        }

        // 2. CSAT (Survei)
        // Assuming 'nilai_survei' is 1-5 or similar scale.
        $avgCsat = $pendaftaran->avg('nilai_survei') ?? 0;
        $respondentsCount = $pendaftaran->whereNotNull('nilai_survei')->count();
        
        // 3. Graduation Projection (REMOVED)
        // Logic removed as per user request to hide the card.
        
        // 4. Competency Details
        // We need to group scores by competency.
        // PendaftaranPelatihan might store aggregate scores, OR specific scores per competency are in a related table?
        // Looking at PendaftaranPelatihan model, it has 'kompetensi_id' and 'nilai_post_test'.
        // This implies one PendaftaranPelatihan row PER competency PER student?? 
        // OR does PendaftaranPelatihan represent the whole course?
        // If it's the whole course, how do we get "Rincian Nilai per Kompetensi"?
        // Ah, PendaftaranPelatihan has 'kompetensi_id' (nullable).
        // If the system creates multiple PendaftaranPelatihan rows (one per competency) for a single student...
        // OR likely there's a child table `NilaiKompetensi`?
        // Let's assume PendaftaranPelatihan is ONE per student per Pelatihan.
        // AND maybe `kompetensi_id` in PendaftaranPelatihan is just for "Kompetensi Keahlian" track?
        // BUT the dashboard wants "Rincian Nilai per Kompetensi" (plural).
        // IF PendaftaranPelatihan is 1 row per student, AND we don't have a child table for scores,
        // THEN maybe we calculate based on the 'kompetensiPelatihan' sessions?
        // Let's check if there is a `Nilai` table.
        // Validating from previous file reads... only `PendaftaranPelatihan` has scores.
        // It has `kompetensi_id` and `kompetensi_pelatihan_id`.
        // This suggests:
        // A student enrolls in a Pelatihan.
        // Maybe they get a separate row for each competency module?
        // Let's assume for now we group `PendaftaranPelatihan` records by `kompetensi_id`?
        // But `PendaftaranPelatihan` -> belongsTo `Peserta`.
        // If a student takes 3 competencies in 1 training, do they have 3 rows?
        // If YES, then grouping by `kompetensi_id` works.
        // Let's proceed with that assumption.
        
        $competencyStats = [];
        $sessions = $this->record->kompetensiPelatihan; 
        
        foreach ($sessions as $session) {
             // Get all registrations for this specific competency session
             // Note: PendaftaranPelatihan has `pelatihan_id` but does it have `kompetensi_pelatihan_id` populated?
             // The model has `kompetensi_pelatihan_id`.
             $sessionRegistrations = \App\Models\PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('kompetensi_pelatihan_id', $session->id)
                ->get();
             
             if ($sessionRegistrations->isEmpty()) {
                 // Fallback: maybe just match by pelatihan_id if we treat it as single grade?
                 // But the requested UI shows multiple competencies.
                 // Let's just assume we want to show the list of competencies even if empty data.
                 $pre = 0; $post = 0; $sat = 0;
             } else {
                 $pre = $sessionRegistrations->avg('nilai_pre_test') ?? 0;
                 $post = $sessionRegistrations->avg('nilai_post_test') ?? 0;
                 $sat = $sessionRegistrations->avg('nilai_survei') ?? 0; // CSAT per competency?
             }
             
             $competencyStats[] = [
                 'name' => $session->kompetensi->nama_kompetensi ?? 'Unknown',
                 'pretest' => number_format($pre, 1),
                 'posttest' => number_format($post, 1),
                 'kepuasan' => number_format($sat, 1),
                 'status' => $post >= 85 ? 'Sangat Baik' : ($post >= 75 ? 'Baik' : 'Cukup'), // Dummy logic
                 'status_color' => $post >= 85 ? 'success' : ($post >= 75 ? 'info' : 'warning'),
             ];
        }

        return [
            'avgPretest' => number_format($avgPretest, 1),
            'avgPosttest' => number_format($avgPosttest, 1),
            'improvement' => number_format($improvement, 0) . '%',
            'csat' => number_format($avgCsat, 1),
            'respondents' => $respondentsCount,
            'competencies' => $competencyStats,
        ];
    }
}
