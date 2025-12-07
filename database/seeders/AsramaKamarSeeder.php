<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// database/seeders/AsramaKamarSeeder.php

namespace Database\Seeders;

use App\Models\Asrama;
use App\Models\Kamar;
use Illuminate\Database\Seeder;

class AsramaKamarSeeder extends Seeder
{
    public function run(): void
    {
        $data = config('asrama_kamar', []);

        foreach ($data as $namaAsrama => $kamars) {
            /** @var \App\Models\Asrama $asrama */
            $asrama = Asrama::firstOrCreate(
                ['nama' => $namaAsrama],
                [
                    'gender' => 'Campur',   // default, bisa kamu edit manual di Filament
                    'alamat' => null,
                ]
            );

            foreach ($kamars as $item) {
                $no   = $item['no'];
                $bed  = $item['bed'];

                $status     = 'Tersedia';
                $totalBeds  = 0;
                $available  = 0;

                if (is_numeric($bed)) {
                    $totalBeds = (int) $bed;
                    $available = (int) $bed;
                    $status    = 'Tersedia';
                } elseif ($bed === 'rusak') {
                    $totalBeds = 0;
                    $available = 0;
                    $status    = 'Rusak';
                } else {
                    // null / unknown → anggap belum di-set
                    $totalBeds = 0;
                    $available = 0;
                    $status    = 'Perbaikan';
                }

                Kamar::updateOrCreate(
                    [
                        'asrama_id'   => $asrama->id,
                        'nomor_kamar' => (string) $no,
                    ],
                    [
                        'lantai'         => 1,        // kalau butuh multi lantai bisa kamu bagi manual
                        'total_beds'     => $totalBeds,
                        'available_beds' => $available,
                        'status'         => $status,
                    ]
                );
            }
        }
    }
}
