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
     * - ambil relasi kompetensiPelatihan (+ instrukturs bila ada pivot)
     * - grupkan jadi kompetensi_items untuk repeater
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Pastikan relasi ter-load (pivot instrukturs dipakai di versi baru)
        $this->record->load('kompetensiPelatihan.instrukturs');

        $relatedRecords = $this->record->kompetensiPelatihan;

        $grouped = $relatedRecords->groupBy(function ($item) {
            return $item->kompetensi_id . '-' . ($item->lokasi ?? 'default');
        });

        $items = [];
        foreach ($grouped as $group) {
            $first = $group->first();

            $instructorIds = [];

            foreach ($group as $recordItem) {
                // 1) Ambil dari pivot relasi instrukturs (versi baru)
                if ($recordItem->relationLoaded('instrukturs') || method_exists($recordItem, 'instrukturs')) {
                    try {
                        $pivotIds = $recordItem->instrukturs?->pluck('id')->toArray() ?? [];
                        $instructorIds = array_merge($instructorIds, $pivotIds);
                    } catch (\Throwable $e) {
                        // kalau relasi tidak valid, skip
                    }
                }

                // 2) Fallback legacy: kolom instruktur_id (versi lama)
                if (!empty($recordItem->instruktur_id)) {
                    $instructorIds[] = $recordItem->instruktur_id;
                }
            }

            $items[] = [
                'kompetensi_id' => $first->kompetensi_id,
                'lokasi'        => $first->lokasi,
                'instruktur_id' => array_values(array_unique(array_filter($instructorIds))),
            ];
        }

        $data['kompetensi_items'] = $items;

        return $data;
    }

    /**
     * Header Action:
     * - Delete saja
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
     * - support multi instruktur via pivot (versi baru)
     * - fallback single instruktur via kolom instruktur_id (versi lama)
     */
    protected function afterSave(): void
    {
        // Hapus semua record lama
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

            // Buat SATU record per item repeater (versi baru)
            $kompetensiPelatihan = $this->record
                ->kompetensiPelatihan()
                ->create($commonData);

            /**
             * Multi instruktur (pivot):
             * instruktur_id berupa array -> attach ke pivot
             */
            if (is_array($instructorIds) && !empty($instructorIds)) {
                // kalau ada relasi instrukturs() gunakan pivot
                if (method_exists($kompetensiPelatihan, 'instrukturs')) {
                    $kompetensiPelatihan->instrukturs()->attach($instructorIds);
                } else {
                    // fallback kalau belum ada pivot: simpan satu-satu ke kolom legacy
                    foreach ($instructorIds as $iid) {
                        $this->record->kompetensiPelatihan()->create(
                            array_merge($commonData, ['instruktur_id' => $iid])
                        );
                    }
                }
            }

            /**
             * Single instruktur (legacy):
             * instruktur_id bukan array -> simpan ke kolom instruktur_id
             */
            if (!is_array($instructorIds) && !empty($instructorIds)) {
                // update record yang barusan dibuat
                $kompetensiPelatihan->update([
                    'instruktur_id' => $instructorIds,
                ]);
            }
        }
    }
}
