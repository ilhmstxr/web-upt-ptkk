<?php

namespace App\Filament\Clusters\Evaluasi\Resources\MateriResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\MateriResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\Storage;

/**
 * ViewMateri
 *
 * Halaman "view" untuk resource Materi (Filament).
 * Pastikan file ini berada di:
 *  app/Filament/Clusters/Evaluasi/Resources/MateriResource/Pages/ViewMateri.php
 */
class ViewMateri extends ViewRecord
{
    protected static string $resource = MateriResource::class;

    /**
     * Header actions (tombol di header)
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Actions (umum) — jika perlu tambahkan custom actions di sini.
     */
    protected function getActions(): array
    {
        return [
            // contoh: Actions\Action::make('custom')->label('Custom')->action('doSomething'),
        ];
    }

    /**
     * Helper: konversi path/file dari DB -> URL publik (storage)
     * gunakan di view jika perlu, mis: $this->getStorageUrl($this->record->file_path)
     */
    protected function getStorageUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        // jika full URL, kembalikan langsung
        if (preg_match('/^https?:\\/\\//i', $path)) {
            return $path;
        }

        // normalisasi: hapus leading "public/" atau "storage/"
        $normalized = preg_replace('#^public\/+#i', '', $path);
        $normalized = preg_replace('#^storage\/+#i', '', $normalized);

        // jika ada di disk public (storage/app/public)
        try {
            if (Storage::disk('public')->exists($normalized)) {
                return Storage::disk('public')->url($normalized); // -> /storage/...
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // cek public/storage/
        if (file_exists(public_path('storage/' . $normalized))) {
            return asset('storage/' . $normalized);
        }

        // cek public/<path>
        if (file_exists(public_path($normalized))) {
            return asset($normalized);
        }

        // fallback: kembalikan /storage/<normalized> (harap storage:link sudah dibuat)
        return '/storage/' . ltrim($normalized, '/');
    }
}
