<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TesOpsiJawaban;

class TesOpsiJawabanSeeder extends Seeder
{
    public function run(): void
    {
        TesOpsiJawaban::insert([
            ['pertanyaan_id' => 1, 'teks_opsi' => 'Bahasa pemrograman', 'is_correct' => true],
            ['pertanyaan_id' => 1, 'teks_opsi' => 'Database', 'is_correct' => false],
            ['pertanyaan_id' => 2, 'teks_opsi' => 'Framework PHP', 'is_correct' => true],
            ['pertanyaan_id' => 2, 'teks_opsi' => 'Sistem Operasi', 'is_correct' => false],
        ]);
    }
}
