<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

use App\Services\AsramaAllocator;
use App\Models\Pelatihan;
use App\Models\Peserta;

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
     * - Delete
     * - Otomasi Asrama
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),

            Actions\Action::make('otomasiAsrama')
                ->label('Otomasi Penempatan Asrama')
                ->icon('heroicon-o-bolt')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Jalankan Otomasi Penempatan Asrama')
                ->modalSubheading('Sistem akan membagi peserta ke kamar asrama sesuai aturan kapasitas & gender.')
                ->modalButton('Jalankan Otomasi')
                ->action(function (AsramaAllocator $allocator) {
                    $this->jalankanOtomasi($this->record->id, $allocator);
                }),
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

    /**
     * Jalankan otomasi penempatan asrama.
     */
    public function jalankanOtomasi(int $pelatihanId, AsramaAllocator $allocator): void
    {
        $pelatihan = Pelatihan::findOrFail($pelatihanId);

        $peserta = Peserta::where('pelatihan_id', $pelatihan->id)
            ->whereDoesntHave('penempatanAsrama')
            ->get();

        $allocator->allocate($pelatihan, $peserta);

        $this->notify('success', 'Otomasi penempatan kamar asrama berhasil dijalankan.');
    }
}
