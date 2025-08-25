<?php

namespace Database\Seeders;

use App\Models\Pertanyaan;
use App\Models\Survey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama untuk menghindari duplikasi
        Survey::query()->delete();
        Pertanyaan::query()->delete();

        $sections = [
            [
                'title' => 'Pendapat Tentang Penyelenggaraan Pelatihan',
                'order' => 1,
                'questions' => [
                    'Bagaimana pendapat Saudara tentang kesesuaian jenis pelayanan dengan penyelenggaraannya',
                    'Bagaimana pendapat Saudara tentang kemudahan prosedur pelayanan penyelenggaraan pelatihan di instansi ini',
                    'Bagaimana pendapat Saudara tentang kedisiplinan petugas / panitia penyelenggara dalam memberikan pelayanan',
                    'Bagaimana pendapat Saudara tentang kesopanan dan keramahan petugas penyelenggara dalam memberikan pelayanan',
                    'Bagaimana pendapat Saudara tentang petugas bengkel dalam memberikan pelayanan',
                    'Bagaimana pendapat Saudara tentang petugas asrama dalam memberikan pelayanan',
                    'Bagaimana pendapat Saudara tentang petugas konsumsi dalam memberikan pelayanan',
                    'Bagaimana pendapat Saudara tentang ketersediaan Sarana dan Prasarana di instansi ini',
                    'Bagaimana pendapat Saudara tentang kebersihan tempat ibadah (mushola) yang ada di instansi ini',
                    'Bagaimana pendapat Saudara tentang kebersihan asrama/ lingkungan asrama',
                    'Bagaimana pendapat Saudara tentang kebersihan kamar mandi/lingkungan kamar mandi?',
                    'Bagaimana pendapat Saudara tentang kebersihan lingkungan taman dan halaman?',
                    'Bagaimana pendapat Saudara tentang kebersihan bengkel / kelas /lingkungan kelas?',
                    'Bagiamana pendapat Saudara tentang kebersihan ruang makan/ lingkungan ruang makan?',
                    'Bagaimana pendapat Saudara tentang keamanan pelayanan di instansi ini?',
                ],
            ],
            [
                'title' => 'Persepsi Terhadap Program Pelatihan',
                'order' => 2,
                'questions' => [
                    'Bagaimana pendapat Saudara tentang waktu yang disediakan dalam penyelenggaraan pelatihan',
                    'Bagaimana pendapat Saudara apakah pelatihan ini bermanfaat bagi anda',
                    'Bagaimana pendapat Saudara tentang ketersediaan bahan-bahan praktek dalam pelaksanaan pelatihan',
                    'Bagaimana pendapat Saudara tentang ketersediaan mesin/peralatan untuk pelatihan',
                    'Bagaimana pendapat Saudara tentang ketersediaan kondisi mesin/peralatan pelatihan',
                    'Bagimana pendapat Saudara tentang ketersediaan materi pelatihan',
                    'Berapa persen materi yang anda serap',
                    'Bagaimana menurut anda apakah perlu penambahan materi pelatihan',
                    'Bagaimana menurut anda apakah perlu pengurangan materi pelatihan',
                    'Apakah materi-materi pelatihan sangat mendukung kompetensi anda',
                ],
            ],
            [
                'title' => 'Penilaian Terhadap Instruktur',
                'order' => 3,
                'questions' => [
                    'Bagaimana pendapat saudara tentang penguasaan materi/ kompetensi pada proses pembelajaran?',
                    'Bagaimana pendapat saudara tentang kedisiplinan/ketepatan waktu Instruktur pada saat pelatihan?',
                    'Bagaimana pendapat saudara tentang metode mengajar Instruktur?',
                    'Bagaimana pendapat saudara tentang sikap dan prilaku instruktur pada saat memberikan pengajaran?',
                    'Bagaimana pendapat saudara tentang kerapian dalam berpakaian instruktur?',
                    'Bagaimana pendapat saudara tentang penggunaan bahasa yang digunakan Instruktur?',
                    'Bagaimana pendapat saudara tentang instruktur dalam memberikan motivasi pada peserta pelatihan?',
                    'Bagaimana pendapat saudara cara instruktur menjawab pertanyaan dari peserta pelatihan?',
                ],
            ]
        ];

        foreach ($sections as $sectionData) {
            $section = Survey::create([
                'title' => $sectionData['title'],
                'slug' => $sectionData['title'],
                'order' => $sectionData['order'],
            ]);

            foreach ($sectionData['questions'] as $index => $questionText) {
                Pertanyaan::create([
                    'survey_id' => $section->id,
                    'text' => $questionText,
                    'order' => $index + 1,
                ]);
            }
        }
    }
}
