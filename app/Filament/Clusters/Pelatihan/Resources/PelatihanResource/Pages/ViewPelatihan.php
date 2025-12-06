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
            '#' => $this->record->nama_pelatihan,
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

    public function addInstructorAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('addInstructor')
            ->label('Tambah Bidang')
            ->icon('heroicon-o-plus')
            ->color('primary')
            ->form([
                \Filament\Forms\Components\Select::make('kompetensi_id')
                    ->label('Kompetensi')
                    ->options(function () {
                        $existingIds = $this->record->kompetensiPelatihan->pluck('kompetensi_id');
                        return \App\Models\Kompetensi::whereNotIn('id', $existingIds)->pluck('nama_kompetensi', 'id');
                    })
                    ->searchable()
                    ->required(),
                \Filament\Forms\Components\Select::make('instruktur_id')
                    ->label('Pilih Instruktur (Opsional)')
                    ->options(\App\Models\Instruktur::query()->pluck('nama', 'id'))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('nama_instruktur', \App\Models\Instruktur::find($state)?->nama)
                    ),
             ])
            ->action(function (array $data) {
                $this->record->kompetensiPelatihan()->create($data);
                \Filament\Notifications\Notification::make()
                    ->title('Instruktur berhasil ditambahkan')
                    ->success()
                    ->send();
            });
    }
}
