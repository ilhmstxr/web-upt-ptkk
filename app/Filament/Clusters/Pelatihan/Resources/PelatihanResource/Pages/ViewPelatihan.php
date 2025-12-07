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
            \Filament\Actions\Action::make('export_excel')
                ->label('Export Peserta')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PerPelatihanExport($this->record->id), 'peserta_' . \Illuminate\Support\Str::slug($this->record->nama_pelatihan) . '.xlsx');
                }),
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
                
                // If no instructor selected, create one record with null instructor? 
                // However, logic implies we want to add instructors.
                // If optional, and empty array?
                
                if (empty($instructors)) {
                    // Create single record without instructor
                    $createData = $data;
                    unset($createData['instruktur_id']);
                    $this->record->kompetensiPelatihan()->create($createData);
                } else {
                    foreach ($instructors as $instructorId) {
                        $createData = $data;
                        $createData['instruktur_id'] = $instructorId;
                        $this->record->kompetensiPelatihan()->create($createData);
                    }
                }

                \Filament\Notifications\Notification::make()
                    ->title('Bidang berhasil ditambahkan')
                    ->success()
                    ->send();
            });
    }
}
