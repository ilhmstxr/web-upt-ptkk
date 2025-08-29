<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TesPercobaan;

class TesPercobaanSeeder extends Seeder
{
    public function run(): void
    {
        TesPercobaan::insert([
            ['user_id' => 1, 'tes_id' => 1, 'started_at' => now(), 'finished_at' => null],
            ['user_id' => 2, 'tes_id' => 1, 'started_at' => now(), 'finished_at' => null],
        ]);
    }
}
