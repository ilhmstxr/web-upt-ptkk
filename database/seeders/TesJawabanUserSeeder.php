<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TesJawabanUser;

class TesJawabanUserSeeder extends Seeder
{
    public function run(): void
    {
        TesJawabanUser::insert([
            ['percobaan_tes_id' => 1, 'pertanyaan_id' => 1, 'opsi_jawaban_id' => 1],
            ['percobaan_tes_id' => 1, 'pertanyaan_id' => 2, 'opsi_jawaban_id' => 3],
        ]);
    }
}
