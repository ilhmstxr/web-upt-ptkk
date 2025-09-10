<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TesPertanyaan;

class TesPertanyaanSeeder extends Seeder
{
    public function run(): void
    {
        TesPertanyaan::insert([
            [
                'tes_id' => 1,
                'teks_pertanyaan' => 'Apa itu PHP?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tes_id' => 1,
                'teks_pertanyaan' => 'Fungsi dari Laravel?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
