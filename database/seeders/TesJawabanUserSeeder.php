<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JawabanUser;

class TesJawabanUserSeeder extends Seeder
{
    public function run(): void
    {
        JawabanUser::insert([
            ['percobaan_tes_id' => 1, 'pertanyaan_id' => 1, 'opsi_jawabans_id' => 1],
            ['percobaan_tes_id' => 1, 'pertanyaan_id' => 2, 'opsi_jawabans_id' => 3],
        ]);
    }
}
