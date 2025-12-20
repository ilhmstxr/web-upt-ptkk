<?php

namespace App\Services;

use App\Models\Asrama;
use Illuminate\Support\Facades\DB;

class AsramaConfigSyncService
{
    public function syncFromConfig(): void
    {
        $config = config('kamar', []);
        if (!is_array($config) || empty($config)) {
            return;
        }

        DB::transaction(function () use ($config) {
            foreach ($config as $asramaName => $rooms) {
                if (!is_array($rooms)) {
                    continue;
                }

                $asrama = Asrama::firstOrCreate(['name' => $asramaName]);

                foreach ($rooms as $room) {
                    $nomor = (int) ($room['no'] ?? 0);
                    if ($nomor <= 0) {
                        continue;
                    }

                    $bedRaw = $room['bed'] ?? null;
                    $lantai = $room['lantai'] ?? $this->inferLantaiFromAsramaName($asramaName);

                    $isNumericBed = is_numeric($bedRaw);
                    $totalBeds = $isNumericBed ? (int) $bedRaw : 0;
                    $isActive = $totalBeds > 0;

                    $status = 'Perbaikan';
                    if ($isNumericBed) {
                        $status = $totalBeds > 0 ? 'Tersedia' : 'Perbaikan';
                    } elseif (is_string($bedRaw) && strtolower($bedRaw) === 'rusak') {
                        $status = 'Rusak';
                    }

                    DB::table('kamars')->updateOrInsert(
                        [
                            'asrama_id'   => $asrama->id,
                            'nomor_kamar' => $nomor,
                        ],
                        [
                            'lantai'         => $lantai,
                            'total_beds'     => $totalBeds,
                            'available_beds' => $totalBeds,
                            'status'         => $status,
                            'is_active'      => $isActive,
                            'created_at'     => now(),
                            'updated_at'     => now(),
                        ]
                    );
                }
            }
        });
    }

    private function inferLantaiFromAsramaName(string $asramaName): ?int
    {
        $name = strtolower($asramaName);
        if (str_contains($name, 'atas')) {
            return 2;
        }
        if (str_contains($name, 'bawah')) {
            return 1;
        }
        return null;
    }
}
