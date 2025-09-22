<?php

namespace App\Filament\Resources\PelatihanResource\Pages;

use App\Filament\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPelatihan extends EditRecord
{
    protected static string $resource = PelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Export semua peserta (PDF → ZIP)
            Actions\Action::make('downloadBulk')
                ->label('Download PDF Pendaftaran (Bulk)')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn () => route('exports.pendaftaran.bulk', $this->record)) // $this->record = Pelatihan
                ->openUrlInNewTab(false),

            // (opsional) Export contoh 1 peserta (mis. peserta terbaru)
            Actions\Action::make('downloadSample')
                ->label('Download 1 PDF Contoh')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn () => route('exports.pendaftaran.sample', $this->record))
                ->openUrlInNewTab(false),

            Actions\DeleteAction::make(),
        ];
    }
}
