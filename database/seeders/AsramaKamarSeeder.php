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
                    ['nama' => $namaAsrama],
                    [
                        'jenis_kelamin' => 'Campur',
                        'alamat'        => null,
                    ]
                );

                foreach ($kamars as $item) {
                    $no  = (string) ($item['no'] ?? '');
                    $bed = $item['bed'] ?? null;

                    if ($no === '') {
                        continue;
                    }

                    // default value kamar tidak siap
                    $status    = 'Perbaikan';
                    $totalBeds = 0;
                    $available = 0;

                    if (is_numeric($bed)) {
                        $totalBeds = (int) $bed;
                        $available = (int) $bed;
                        $status    = 'Tersedia';
                    } elseif (is_string($bed) && strtolower($bed) === 'rusak') {
                        $status    = 'Rusak';
                    }

                    // ✅ updateOrCreate pakai koneksi model Kamar
                    Kamar::query()->updateOrCreate(
                        [
                            'asrama_id'   => $asrama->id,
                            'nomor_kamar' => $no,
                        ],
                        [
                            'lantai'         => (int) ($item['lantai'] ?? 1), // kalau nanti config punya lantai
                            'total_beds'     => $totalBeds,
                            'available_beds' => $available,
                            'status'         => $status,
                        ]
                    );
                }
            }
        });

        $this->command?->info('Seeder Asrama + Kamar selesai.');
    }
}
