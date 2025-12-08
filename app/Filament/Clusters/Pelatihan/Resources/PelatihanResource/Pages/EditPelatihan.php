<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPelatihan extends EditRecord
{
    protected static string $resource = PelatihanResource::class;

    public function getBreadcrumb(): string
    {
        return \Illuminate\Support\Str::limit(parent::getBreadcrumb(), 40);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing KompetensiPelatihan records with instructors
        $this->record->load('kompetensiPelatihan.instrukturs');
        
        $relatedRecords = $this->record->kompetensiPelatihan;
        
        $grouped = $relatedRecords->groupBy(function ($item) {
            return $item->kompetensi_id . '-' . ($item->lokasi ?? 'default');
        });

        $items = [];
        foreach ($grouped as $key => $group) {
            $first = $group->first();
            
            // Collect all instructor IDs from all records in this group
            $instructorIds = [];
            foreach ($group as $recordItem) {
                // Merge IDs from the pivot relation
                $instructorIds = array_merge($instructorIds, $recordItem->instrukturs->pluck('id')->toArray());
            }
            // If the old column existed (legacy data not yet migrated properly? - no, we rely on relation now)
            
            $items[] = [
                'kompetensi_id' => $first->kompetensi_id,
                'lokasi' => $first->lokasi,
                'instruktur_id' => array_values(array_unique($instructorIds)), 
            ];
        }

        $data['kompetensi_items'] = $items;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Delete all old records to consolidate/update
        $this->record->kompetensiPelatihan()->delete();
        
        $data = $this->data;
        if (isset($data['kompetensi_items']) && is_array($data['kompetensi_items'])) {
            foreach ($data['kompetensi_items'] as $item) {
                $instructorIds = $item['instruktur_id'] ?? [];
                $commonData = [
                    'kompetensi_id' => $item['kompetensi_id'],
                    'lokasi' => $item['lokasi'] ?? 'UPT-PTKK',
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
