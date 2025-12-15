<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tes;
use App\Models\Pertanyaan;
use App\Models\Pelatihan;
use Illuminate\Support\Facades\DB;

class MonevTemplateSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // 1. Ensure a Pelatihan exists (required for Tes)
            $pelatihan = Pelatihan::first();
            if (!$pelatihan) {
                $this->command->info("No Pelatihan found. Creating a dummy Pelatihan for the template.");
                $pelatihan = Pelatihan::create([
                    // Minimal fields based on assumption, might need more based on Pelatihan model
                    'nama' => 'TEMPLATE PELATIHAN',
                    'tanggal_mulai' => now(),
                    'tanggal_selesai' => now()->addDays(1),
                    'lokasi' => 'Template',
                ]);
            }

            // 2. Create or Find the Master Template Tes
            $judul = 'MASTER TEMPLATE MONEV';
            $tes = Tes::where('judul', $judul)->first();

            if ($tes) {
                $this->command->info("Updating existing Template Tes: $judul");
                // Optional: Clear existing questions to reset them
                $tes->pertanyaan()->delete();
            } else {
                $this->command->info("Creating new Template Tes: $judul");
                $tes = Tes::create([
                    'judul' => $judul,
                    'deskripsi' => 'Template Master untuk Evaluasi/Monev. Jangan gunakan Tes ini untuk ujian nyata, tapi gunakan sebagai sumber copy/clone.',
                    'tipe' => 'survei',
                    'pelatihan_id' => $pelatihan->id,
                ]);
            }

            // 3. Define the Fixed Questions
            $questions = [
                // Kategori: Pelayanan
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang kesesuaian jenis pelayanan dengan penyelenggaraannya.........'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang kemudahan prosedur pelayanan penyelenggaraan pelatihan di instansi ini.......'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang kedisiplinan petugas / panitia penyelenggara dalam memberikan pelayanan.........'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang kesopanan dan keramahan petugas penyelenggara dalam memberikan pelayanan..........'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang petugas bengkel dalam memberikan pelayanan........'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang petugas asrama dalam memberikan pelayanan.........'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang petugas konsumsi dalam memberikan pelayanan.......'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang ketersediaan Sarana dan Prasarana di instansi ini.......'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang kebersihan tempat ibadah (mushola) yang ada di instansi ini.......'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang kebersihan asrama/ lingkungan asrama........'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang kebersihan kamar mandi/lingkungan kamar mandi......'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang kebersihan lingkungan taman dan halaman......'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang kebersihan bengkel / kelas /lingkungan kelas......'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang kebersihan ruang makan/ lingkungan ruang makan ......'],
                ['kategori' => 'pelayanan', 'teks' => 'Bagaimana pendapat Saudara tentang keamanan pelayanan di instansi ini....'],
                ['kategori' => 'pelayanan', 'teks' => 'Pesan Dan Kesan :', 'tipe_jawaban' => 'teks_bebas'],

                // Kategori: Fasilitas
                ['kategori' => 'fasilitas', 'teks' => 'Bagaimana pendapat Saudara tentang waktu yang disediakan dalam penyelenggaraan pelatihan.'],
                ['kategori' => 'fasilitas', 'teks' => 'Bagaimana pendapat Saudara apakah pelatihan ini bermanfaat bagi anda.'],
                ['kategori' => 'fasilitas', 'teks' => 'Bagaimana pendapat Saudara tentang ketersediaan bahan-bahan praktek dalam pelaksanaan pelatihan'],
                ['kategori' => 'fasilitas', 'teks' => 'Bagaimana pendapat Saudara tentang ketersediaan mesin/peralatan untuk pelatihan.'],
                ['kategori' => 'fasilitas', 'teks' => 'Bagaimana pendapat Saudara tentang ketersediaan kondisi mesin/peralatan pelatihan.'],
                ['kategori' => 'fasilitas', 'teks' => 'Bagaimana pendapat Saudara tentang ketersediaan materi pelatihan'],
                ['kategori' => 'fasilitas', 'teks' => 'Berapa persen materi yang anda serap'],
                ['kategori' => 'fasilitas', 'teks' => 'Bagaimana menurut anda apakah perlu penambahan materi pelatihan'],
                ['kategori' => 'fasilitas', 'teks' => 'Bagaimana menurut anda apakah perlu pengurangan materi pelatihan'],
                ['kategori' => 'fasilitas', 'teks' => 'Apakah materi-materi pelatihan sangat mendukung kompetensi anda'],
                ['kategori' => 'fasilitas', 'teks' => 'Pesan Dan Kesan :', 'tipe_jawaban' => 'teks_bebas'],

                // Kategori: Instruktur
                ['kategori' => 'instruktur', 'teks' => 'Bagaimana pendapat saudara tentang penguasaan materi/ kompetensi pada proses pembelajaran'],
                ['kategori' => 'instruktur', 'teks' => 'Bagaimana pendapat saudara tentang kedisiplinan/ketepatan waktu Instruktur pada saat pelatihan'],
                ['kategori' => 'instruktur', 'teks' => 'Bagaimana pendapat saudara tentang metode mengajar Instruktur'],
                ['kategori' => 'instruktur', 'teks' => 'bagaimana pendapat saudara tentang sikap dan prilaku instruktur pada saat memberikan pengajaran'],
                ['kategori' => 'instruktur', 'teks' => 'bagaimana pendapat saudara tentang kerapian dalam berpakaian instruktur'],
                ['kategori' => 'instruktur', 'teks' => 'Bagaimana pendapat saudara tentang penggunaan bahasa yang digunakan Instruktur'],
                ['kategori' => 'instruktur', 'teks' => 'bagaimana pendapat saudara tentang instruktur dalam memberikan motivasi pada peserta pelatihan'],
                ['kategori' => 'instruktur', 'teks' => 'Bagaimana pendapat saudara cara instruktur menjawab pertanyaan dari peserta pelatihan'],
                ['kategori' => 'instruktur', 'teks' => 'Intruktur terfavorit', 'tipe_jawaban' => 'teks_bebas'],
                ['kategori' => 'instruktur', 'teks' => 'Pesan dan Kesan', 'tipe_jawaban' => 'teks_bebas'],
            ];

            // 4. Insert Questions
            foreach ($questions as $index => $q) {
                Pertanyaan::create([
                    'tes_id' => $tes->id,
                    'nomor' => $index + 1,
                    'kategori' => $q['kategori'],
                    'teks_pertanyaan' => $q['teks'],
                    'tipe_jawaban' => $q['tipe_jawaban'] ?? 'skala_likert',
                ]);
            }

            $this->command->info("Successfully seeded " . count($questions) . " questions into 'MASTER TEMPLATE MONEV'.");
        });
    }
}
