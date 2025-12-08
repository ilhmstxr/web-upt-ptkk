<?php

namespace Database\Seeders;

use App\Models\Asrama;
use App\Models\Kamar;
use Illuminate\Database\Seeder;

class AsramaKamarSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ karena file kamu config/kamar.php
        $data = config('kamar', []);

        foreach ($data as $namaAsrama => $kamars) {

            $asrama = Asrama::updateOrCreate(
                ['nama' => $namaAsrama],
                [
                    'jenis_kelamin' => 'Campur', // bisa kamu edit manual nanti
                    'alamat' => null,
                ]
            );

            foreach ($kamars as $item) {
                $no  = (string) $item['no'];
                $bed = $item['bed'];

                if ($bed === 'rusak') {
                    $totalBeds = 0;
                    $available = 0;
                    $status    = 'Rusak';
                } else {
                    // ✅ numeric atau null → default 4
                    $totalBeds = is_numeric($bed) ? (int) $bed : 4;
                    $available = $totalBeds;
                    $status    = 'Tersedia';
                }

                Kamar::updateOrCreate(
                    [
                        'asrama_id'   => $asrama->id,
                        'nomor_kamar' => $no,
                    ],
                    [
                        'lantai'         => 1,
                        'total_beds'     => $totalBeds,
                        'available_beds' => $available,
                        'status'         => $status,
                    ]
                );
            }
        }
    }
}
