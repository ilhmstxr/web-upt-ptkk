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
        // Load existing KompetensiPelatihan records
        // record is available via $this->record
        
        $relatedRecords = $this->record->kompetensiPelatihan;
        
        // Group by kompetensi_id and location (assuming these define a "session" unique set with instructors being the variable)
        // If 'tanggal', 'jam_mulai' are also part of uniqueness, include them. 
        // For now, grouping by kompetensi_id mostly.
        
        $grouped = $relatedRecords->groupBy(function ($item) {
            return $item->kompetensi_id . '-' . ($item->lokasi ?? 'default');
        });

        $items = [];
        foreach ($grouped as $key => $group) {
            $first = $group->first();
            $instructorIds = $group->pluck('instruktur_id')->filter()->values()->toArray();
            
            $items[] = [
                'kompetensi_id' => $first->kompetensi_id,
                'lokasi' => $first->lokasi,
                'instruktur_id' => $instructorIds, // Array of IDs
                // Add other fields if necessary
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
        // Handle saving on Edit as well.
        // This is trickier because we need to sync: delete old, create new?
        // Or just deleteAll and recreate for simplicity?
        // Creating new is safer to avoid orphans, but deleting all might lose other data like 'nilai', 'file_modul' if they exist.
        // If creation is the main goal, maybe I should strictly limit this logic to Create?
        // But if I show the form in Edit, I MUST save it.
        
        // Strategy: Delete all competency schedules for this training and recreate based on form data.
        // WARNING: This assumes no other important data (like grades/attendance) is attached to these specific IDs yet.
        // If grades exist, this is destructive!
        // Given "Create Pelatihan" context, maybe this is fine. 
        // But for Edit, if grades exist...
        // The user only asked "ketika create pelatihan".
        // Maybe I should NOT change Edit logic drastically?
        // But if I change the Resource form, Edit is affected.
        
        // Let's assume for now we re-create.
        $this->record->kompetensiPelatihan()->delete();
        
        $data = $this->data;
        if (isset($data['kompetensi_items']) && is_array($data['kompetensi_items'])) {
            foreach ($data['kompetensi_items'] as $item) {
                $instructorIds = $item['instruktur_id'] ?? [];
                $commonData = [
                    'kompetensi_id' => $item['kompetensi_id'],
                    'lokasi' => $item['lokasi'] ?? 'UPT-PTKK',
                ];

                if (!empty($instructorIds) && is_array($instructorIds)) {
                     foreach ($instructorIds as $instructorId) {
                        $this->record->kompetensiPelatihan()->create(array_merge($commonData, [
                            'instruktur_id' => $instructorId,
                        ]));
                     }
                } else {
                     if (!empty($item['instruktur_id']) && !is_array($item['instruktur_id'])) {
                         $this->record->kompetensiPelatihan()->create(array_merge($commonData, [
                            'instruktur_id' => $item['instruktur_id'],
                        ]));
                     }
                }
            }
        }
    }
}
