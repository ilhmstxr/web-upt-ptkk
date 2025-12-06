<?php

namespace Database\Seeders;

use App\Models\Kompetensi;
use App\Models\Pelatihan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KompetensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kompetensi = [
            // batch 1
            [
                'nama_kompetensi' => 'Tata Boga',
                'deskripsi' => 'Kompetensi keahlian yang mengajarkan teknik memasak, penyajian, dan manajemen makanan.',
            ],
            [
                'nama_kompetensi' => 'Tata Busana',
                'deskripsi' => 'Kompetensi keahlian untuk menjadi desainer busana yang handal, dari membuat pola hingga jahitan akhir.',
            ],
            [
                'nama_kompetensi' => 'Kecantikan',
                'deskripsi' => 'Kompetensi keahlian yang mengajarkan teknik perawatan kecantikan, mulai dari wajah hingga tubuh.',
            ],
            [
                'nama_kompetensi' => 'Teknik Pendingin dan Tata Udara',
                'deskripsi' => 'Kompetensi keahlian yang berfokus pada instalasi dan perawatan sistem pendingin dan tata udara.',
            ],

            // batch 2
            [
                'nama_kompetensi' => 'Web Desain',
                'deskripsi' => 'Kompetensi keahlian yang mengajarkan pembuatan dan pengembangan situs web, termasuk desain antarmuka pengguna dan pengalaman pengguna.',
            ],
            [
                'nama_kompetensi' => 'Desain Grafis',
                'deskripsi' => 'Kompetensi keahlian yang mengajarkan pembuatan dan pengembangan karya desain grafis seperti poster, brosur, dan logo.',
            ],
            [
                'nama_kompetensi' => 'Animasi',
                'deskripsi' => 'Kompetensi keahlian yang mengajarkan pembuatan dan pengembangan animasi, seperti animasi 2D dan animasi 3D.',
            ],
            [
                'nama_kompetensi' => 'Fotografi',
                'deskripsi' => 'Kompetensi keahlian yang mengajarkan teknik pengambilan gambar, pengeditan foto, dan pencetakan.',
            ],
            [
                'nama_kompetensi' => 'Videografi',
                'deskripsi' => 'Kompetensi keahlian yang mengajarkan teknik pembuatan dan pengeditan video, termasuk sinematografi dan pascaproduksi.',
            ],
        ];



        \App\Models\Kompetensi::insert($kompetensi);
    }
}
