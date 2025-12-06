<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPelatihan extends ViewRecord
{
    protected static string $resource = PelatihanResource::class;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.pages.view-pelatihan';

    public function addInstructorAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('addInstructor')
            ->label('Tambah Instruktur')
            ->icon('heroicon-o-plus')
            ->color('primary')
            ->form([
                \Filament\Forms\Components\Select::make('kompetensi_id')
                    ->label('Kompetensi')
                    ->options(\App\Models\Kompetensi::query()->pluck('nama_kompetensi', 'id'))
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
