<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\Pelatihan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bidang = [
            // batch 1
            [
                'nama_bidang' => 'Tata Boga',
                'deskripsi' => 'Bidang keahlian yang mengajarkan teknik memasak, penyajian, dan manajemen makanan.',
            ],
            [
                'nama_bidang' => 'Tata Busana',
                'deskripsi' => 'Bidang keahlian untuk menjadi desainer busana yang handal, dari membuat pola hingga jahitan akhir.',
            ],
            [
                'nama_bidang' => 'Kecantikan',
                'deskripsi' => 'Bidang keahlian yang mengajarkan teknik perawatan kecantikan, mulai dari wajah hingga tubuh.',
            ],
            [
                'nama_bidang' => 'Teknik Pendingin dan Tata Udara',
                'deskripsi' => 'Bidang keahlian yang berfokus pada instalasi dan perawatan sistem pendingin dan tata udara.',
            ],

            // batch 2
            [
                'nama_bidang' => 'Web Desain',
                'deskripsi' => 'Bidang keahlian yang mengajarkan pembuatan dan pengembangan situs web, termasuk desain antarmuka pengguna dan pengalaman pengguna.',
            ],
            [
                'nama_bidang' => 'Desain Grafis',
                'deskripsi' => 'Bidang keahlian yang mengajarkan pembuatan dan pengembangan karya desain grafis seperti poster, brosur, dan logo.',
            ],
            [
                'nama_bidang' => 'Animasi',
                'deskripsi' => 'Bidang keahlian yang mengajarkan pembuatan dan pengembangan animasi, seperti animasi 2D dan animasi 3D.',
            ],
            [
                'nama_bidang' => 'Fotografi',
                'deskripsi' => 'Bidang keahlian yang mengajarkan teknik pengambilan gambar, pengeditan foto, dan pencetakan.',
            ],
            [
                'nama_bidang' => 'Videografi',
                'deskripsi' => 'Bidang keahlian yang mengajarkan teknik pembuatan dan pengeditan video, termasuk sinematografi dan pascaproduksi.',
            ],
        ];



        Bidang::insert($bidang);
    }
}
