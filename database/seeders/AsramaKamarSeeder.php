<?php

namespace Database\Seeders;

use App\Models\Asrama;
use App\Models\Kamar;
use Illuminate\Database\Seeder;

class AsramaKamarSeeder extends Seeder
{
    public function run(): void
    {
        // FIX: ambil dari config/kamar.php
        $data = config('kamar', []);

        foreach ($data as $namaAsrama => $kamars) {
            $asrama = Asrama::firstOrCreate(
                ['nama' => $namaAsrama],
                [
                    'jenis_kelamin' => 'Campur',
                    'alamat' => null,
                ]
            );

            foreach ($kamars as $item) {
                $no  = $item['no'];
                $bed = $item['bed'];

                $status    = 'Tersedia';
                $totalBeds = 0;
                $available = 0;

                if (is_numeric($bed)) {
                    $totalBeds = (int) $bed;
                    $available = (int) $bed;
                    $status    = 'Tersedia';
                } elseif ($bed === 'rusak') {
                    $status    = 'Rusak';
                } else {
                    // null → belum diset, jangan dipakai allocator
                    $status    = 'Perbaikan';
                }

                Kamar::updateOrCreate(
                    [
                        'asrama_id'   => $asrama->id,
                        'nomor_kamar' => (string) $no,
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
