<?php

namespace App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource;
use App\Services\AsramaAllocator;
use App\Models\PenempatanAsrama;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Mail;
use App\Mail\PenempatanMail;

class ViewPenempatanAsrama extends ViewRecord
{
    protected static string $resource = PenempatanAsramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('finalize_penempatan')
                ->label('Finalize Penempatan Asrama')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $pelatihanId = $this->record->id;

                    $allocator = app(AsramaAllocator::class);

                    // 1) generate kamar dari config / session
                    $kamarConfig = session('kamar') ?? config('kamar');
                    $allocator->generateRoomsFromConfig($pelatihanId, $kamarConfig);

                    // 2) allocate peserta ke DB
                    $result = $allocator->allocatePesertaPerPelatihan($pelatihanId);

                    // 3) kirim email untuk yang BARU ditempatkan
                    $penempatanBaru = PenempatanAsrama::with('pendaftaran.peserta', 'kamar.asrama', 'pelatihan')
                        ->whereIn('pendaftaran_id', collect($result['details'])->pluck('pendaftaran_id'))
                        ->get();

                    foreach ($penempatanBaru as $p) {
                        $email = $p->pendaftaran->peserta->email ?? null;
                        if ($email) {
                            Mail::to($email)->send(new PenempatanMail($p));
                        }
                    }

                    Notification::make()
                        ->title('Penempatan selesai')
                        ->body("OK={$result['ok']}, skipped={$result['skipped_already_assigned']}, gagal={$result['failed_full']}")
                        ->success()
                        ->send();
                }),
        ];
    }
}
