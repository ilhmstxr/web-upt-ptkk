<?php

namespace Database\Seeders;

use App\Models\Asrama;
use App\Models\Kamar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsramaKamarSeeder extends Seeder
{
    public function run(): void
    {
        $data = config('kamar', []);

        if (empty($data) || !is_array($data)) {
            $this->command?->warn('Config kamar kosong, seeder dibatalkan.');
            return;
        }

        // ✅ pastikan seeder pakai koneksi dari model (multi DB safe)
        $asramaConn = (new Asrama)->getConnectionName();
        $kamarConn  = (new Kamar)->getConnectionName();

        DB::connection($asramaConn)->transaction(function () use ($data, $kamarConn) {

            foreach ($data as $namaAsrama => $kamars) {

                /** @var Asrama $asrama */
                $asrama = Asrama::query()->firstOrCreate(
                    ['name' => $namaAsrama],
                    []
                );

                foreach ($kamars as $item) {
                    $no  = (string) ($item['no'] ?? '');
                    $bed = $item['bed'] ?? null;

                    if ($no === '') {
                        continue;
                    }

                    // Map status to is_active
                    $isActive = true;
                    $totalBeds = 0;

                    if (is_numeric($bed)) {
                        $totalBeds = (int) $bed;
                        $isActive  = true;
                    } elseif (is_string($bed) && strtolower($bed) === 'rusak') {
                        $isActive = false;
                    }

                    // ✅ updateOrCreate pakai koneksi model Kamar
                    Kamar::query()->updateOrCreate(
                        [
                            'asrama_id'   => $asrama->id,
                            'nomor_kamar' => $no,
                        ],
                        [
                            'total_beds' => $totalBeds,
                            'is_active'  => $isActive,
                        ]
                    );
                }
            }
        });

        $this->command?->info('Seeder Asrama + Kamar selesai.');
    }
}
