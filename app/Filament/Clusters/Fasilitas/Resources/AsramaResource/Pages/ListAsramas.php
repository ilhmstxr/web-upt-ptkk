<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Services\AsramaAllocator;
use App\Models\Pelatihan;
use App\Models\Peserta;

class EditPelatihan extends EditRecord
{
    protected static string $resource = PelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),

            // 🔋 Tombol Otomasi Penempatan Asrama
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
     * Jalankan otomasi penempatan asrama untuk pelatihan ini.
     */
    public function jalankanOtomasi(int $pelatihanId, AsramaAllocator $allocator): void
    {
        $pelatihan = Pelatihan::findOrFail($pelatihanId);

        // Ambil peserta yang TERDAFTAR di pelatihan ini & BELUM punya penempatan asrama
        $peserta = Peserta::where('pelatihan_id', $pelatihan->id)
            ->whereDoesntHave('penempatanAsrama')
            ->get();

        // Jalankan service allocator (logika pengisian kamar)
        $allocator->allocate($pelatihan, $peserta);

        // Notifikasi ke user Filament
        $this->notify('success', 'Otomasi penempatan kamar asrama berhasil dijalankan.');
    }
}
