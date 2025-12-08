<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTes extends EditRecord
{
    protected static string $resource = TesResource::class;

    protected static string $view = 'filament.clusters.evaluasi.resources.tes-resource.pages.edit-tes';

    public function getMaxContentWidth(): ?string
    {
        return '7xl';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save changes')
                ->action('save'),
            Actions\ReplicateAction::make()
                ->label('Duplicate')
                ->beforeReplicaSaved(function (\Illuminate\Database\Eloquent\Model $replica) {
                    $replica->judul = $replica->judul . ' - Copy';
                })
                ->after(function (\Illuminate\Database\Eloquent\Model $replica, \Illuminate\Database\Eloquent\Model $record) {
                    // Deep copy relationships (Pertanyaan)
                    foreach ($record->pertanyaan as $pertanyaan) {
                        $newPertanyaan = $pertanyaan->replicate();
                        $newPertanyaan->tes_id = $replica->id;
                        $newPertanyaan->save();
                    }
                })
                ->successNotificationTitle('Tes berhasil diduplikasi'),
            Actions\DeleteAction::make(),
        ];
    }
}
