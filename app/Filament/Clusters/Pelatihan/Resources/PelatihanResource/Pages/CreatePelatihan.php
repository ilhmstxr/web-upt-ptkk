<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use \Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreatePelatihan extends CreateRecord
{
    use HasWizard;

    protected static string $resource = PelatihanResource::class;

    protected function getSteps(): array
    {
        return PelatihanResource::getWizardSteps();
    }
    protected function afterCreate(): void
    {
        $data = $this->data;

        // Manual saving for "kompetensi_items" Repeater
        if (isset($data['kompetensi_items']) && is_array($data['kompetensi_items'])) {
            foreach ($data['kompetensi_items'] as $item) {
                // $item['instruktur_id'] is an array due to multiple()
                $instructorIds = $item['instruktur_id'] ?? [];
                
                // Common fields for all rows in this group
                $commonData = [
                    'kompetensi_id' => $item['kompetensi_id'],
                    'lokasi' => $item['lokasi'] ?? 'UPT-PTKK',
                    // Add other fields if present in schema
                ];

                if (!empty($instructorIds) && is_array($instructorIds)) {
                    foreach ($instructorIds as $instructorId) {
                        $this->record->kompetensiPelatihan()->create(array_merge($commonData, [
                            'instruktur_id' => $instructorId,
                        ]));
                    }
                } else {
                     // If no instructor selected, or single value (edge case)
                     // But we enforced required and array.
                     // Just in case:
                     if (!empty($item['instruktur_id']) && !is_array($item['instruktur_id'])) {
                         $this->record->kompetensiPelatihan()->create(array_merge($commonData, [
                            'instruktur_id' => $item['instruktur_id'],
                        ]));
                     } else {
                         // Should not happen if required, but if it does, create without instructor?
                         // Schema says required.
                     }
                }
            }
        }
    }
}
