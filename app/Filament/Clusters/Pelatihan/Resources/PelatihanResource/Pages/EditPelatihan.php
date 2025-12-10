<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditPelatihan extends EditRecord
{
    protected static string $resource = PelatihanResource::class;

    public function getBreadcrumb(): string
    {
        return Str::limit(parent::getBreadcrumb(), 40);
    }

    /**
     * Saat form edit dibuka:
     * - ambil relasi kompetensiPelatihan
     * - grupkan jadi kompetensi_items untuk repeater
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $relatedRecords = $this->record->kompetensiPelatihan;

        $grouped = $relatedRecords->groupBy(function ($item) {
            return $item->kompetensi_id . '-' . ($item->lokasi ?? 'default');
        });

        $items = [];
        foreach ($grouped as $group) {
            $first = $group->first();
            $instructorIds = $group->pluck('instruktur_id')->filter()->values()->toArray();

            $items[] = [
                'kompetensi_id' => $first->kompetensi_id,
                'lokasi'        => $first->lokasi,
                'instruktur_id' => $instructorIds,
            ];
        }

        $data['kompetensi_items'] = $items;

        return $data;
    }

    /**
     * Header Action:
     * - Delete saja (otomasi asrama dihapus biar tidak dobel)
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Setelah disimpan:
     * - hapus jadwal kompetensi lama
     * - buat ulang dari repeater kompetensi_items
     */
    protected function afterSave(): void
    {
        $this->record->kompetensiPelatihan()->delete();

        $data = $this->data;

        if (!isset($data['kompetensi_items']) || !is_array($data['kompetensi_items'])) {
            return;
        }

        foreach ($data['kompetensi_items'] as $item) {
            $instructorIds = $item['instruktur_id'] ?? [];

            $commonData = [
                'kompetensi_id' => $item['kompetensi_id'],
                'lokasi'        => $item['lokasi'] ?? 'UPT-PTKK',
            ];

            // multi instruktur
            if (!empty($instructorIds) && is_array($instructorIds)) {
                foreach ($instructorIds as $instructorId) {
                    $this->record->kompetensiPelatihan()->create(
                        array_merge($commonData, [
                            'instruktur_id' => $instructorId,
                        ])
                    );
                }
            }

            // single instruktur
            if (!is_array($instructorIds) && !empty($instructorIds)) {
                $this->record->kompetensiPelatihan()->create(
                    array_merge($commonData, [
                        'instruktur_id' => $instructorIds,
                    ])
                );
            }
        }
    }
}
