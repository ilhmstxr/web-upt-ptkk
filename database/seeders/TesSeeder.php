<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tes;

class TesSeeder extends Seeder
{
    public function run(): void
    {
        Tes::insert([
            [
                'judul' => 'Pre-Test PHP',
                'tipe' => 'pre-test',
                'bidang' => 'Programming',
                'pelatihan' => 'PHP Dasar',
                'durasi_menit' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Post-Test PHP',
                'tipe' => 'post-test',
                'bidang' => 'Programming',
                'pelatihan' => 'PHP Lanjut',
                'durasi_menit' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
