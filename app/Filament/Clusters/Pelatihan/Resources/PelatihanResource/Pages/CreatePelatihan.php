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

                // Create ONE record per item
                $kompetensiPelatihan = $this->record->kompetensiPelatihan()->create($commonData);

                // Attach instructors via Pivot
                if (!empty($instructorIds) && is_array($instructorIds)) {
                     $kompetensiPelatihan->instrukturs()->attach($instructorIds);
                }
            }
        }
    }
}
