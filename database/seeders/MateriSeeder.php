<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use Illuminate\Support\Str;

class MateriSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['judul'=>'Pengenalan Vokasi','slug'=>'pengenalan-vokasi','order'=>1,'durasi'=>30,'deskripsi'=>'Pengenalan...'],
            ['judul'=>'Keselamatan Kerja','slug'=>'keselamatan-kerja','order'=>2,'durasi'=>45,'deskripsi'=>'Keselamatan...'],
            ['judul'=>'Teknik Dasar','slug'=>'teknik-dasar','order'=>3,'durasi'=>60,'deskripsi'=>'Teknik dasar...'],
        ];

        foreach ($items as $it) {
            Materi::updateOrCreate(['slug'=>$it['slug']], $it);
        }
    }
}

