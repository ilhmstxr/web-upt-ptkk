<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;

class ViewPelatihan extends ViewRecord
{
    use \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\Concerns\BuildsLikertData;

    protected static string $resource = PelatihanResource::class;

    protected static string $view =
        'filament.clusters.pelatihan.resources.pelatihan-resource.pages.view-pelatihan';

    /**
     * ======================
     * TITLE & BREADCRUMB
     * ======================
     */
    public function getTitle(): string|Htmlable
    {
        return $this->record->nama_pelatihan;
    }

    public function getBreadcrumbs(): array
    {
        return [
            PelatihanResource::getUrl('index') => 'Manajemen Pelatihan',
            '#' => \Str::limit($this->record->nama_pelatihan, 40),
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
                    ->url(fn () => route('export.template.rekap-pelatihan', [
                        'pelatihanId' => $this->record->id,
                    ]))
                    ->openUrlInNewTab(),

                \Filament\Actions\Action::make('export_excel')
                    ->label('Peserta (Excel)')
                    ->icon('heroicon-o-table-cells')
                    ->url(fn () => route('export.template.peserta-excel', [
                        'pelatihanId' => $this->record->id,
                    ]))
                    ->openUrlInNewTab(),

                \Filament\Actions\Action::make('export_instruktur')
                    ->label('Daftar Instruktur (PDF)')
                    ->icon('heroicon-o-users')
                    ->url(fn () => route('export.template.daftar-instruktur', [
                        'pelatihanId' => $this->record->id,
                    ]))
                    ->openUrlInNewTab(),

                \Filament\Actions\Action::make('export_biodata')
                    ->label('Biodata Peserta (PDF)')
                    ->icon('heroicon-o-identification')
                    ->url(fn () => route('export.template.biodata-peserta', [
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

    /**
     * ======================
     * 🔥 HEADER WIDGETS
     * ======================
     */
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PelatihanStatsOverview::class,
            \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PrePostTestChart::class,
            \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\KelulusanChart::class,
        ];
    }

    /**
     * ======================
     * SUBHEADING
     * ======================
     */
    public function getSubheading(): string|Htmlable|null
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
     * EVALUATION DATA (LIKERT, PRE, POST)
     * ======================
     */
    public function getEvaluationData(): array
    {
        $pelatihanId = $this->record->id;

        $avgPre = \App\Models\PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
            ->avg('nilai_pre_test') ?? 0;

        $avgPost = \App\Models\PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
            ->avg('nilai_post_test') ?? 0;

        $improvement = $avgPre > 0
            ? (($avgPost - $avgPre) / $avgPre) * 100
            : 0;

        $avgCsat = \App\Models\PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
            ->whereNotNull('nilai_survey')
            ->avg('nilai_survey') ?? 0;

        $respondents = \App\Models\PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
            ->whereNotNull('nilai_survey')
            ->count();

        return [
            'avgPretest'   => number_format($avgPre, 1),
            'avgPosttest'  => number_format($avgPost, 1),
            'improvement'  => number_format($improvement, 0) . '%',
            'csat'         => number_format($avgCsat, 1),
            'respondents'  => $respondents,
            'hasData'      => $respondents > 0 || $avgPre > 0 || $avgPost > 0,
        ];
    }
}
