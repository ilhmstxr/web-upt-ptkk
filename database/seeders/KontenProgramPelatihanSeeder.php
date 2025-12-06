<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KontenProgramPelatihan;

class KontenProgramPelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            'Mobil Training Unit',
            'Diklat Peningkatan Kompetensi',
            'Sertifikasi Berbasis KKNI Bertaraf Nasional',
        ];

        foreach ($programs as $judul) {
            KontenProgramPelatihan::firstOrCreate(['judul' => $judul]);
        }

    }
}
