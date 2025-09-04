<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreTesTptuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insert data ke tabel tes
        $tesId = DB::table('tes')->insertGetId([
            'judul' => 'Pre Tes Mobile Training Unit (MTU) - TPTU',
            'deskripsi' => 'PETUNJUK UMUM : 
1. Jumlah Soal sebanyak 20
2. Waktu 60 menit

Nama Lengkap *
Contoh Penulisan: BINTANG PUTRA PURNAMA

Nama Sekolah Asal *
Contoh Penulisan: SMK NEGERI 1 SURABAYA

Setelah bagian 1
Bagian 2 dari 2
Selamat mengerjakan, semoga sukses!',
            'tipe' => 'tes',
            'sub_tipe' => 'pre-test',
            'bidang_id' => 1, // sesuaikan dengan data bidang yang ada
            'pelatihan_id' => 1, // sesuaikan dengan data pelatihan yang ada
            'durasi_menit' => 60,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Data pertanyaan & jawaban
        $soals = [
            [
                'teks' => 'Mesin 3R adalah mesin yang digunakan untuk melakukan proses....',
                'opsi' => [
                    'A. Recovery, recycling dan recharging pada mesin pendingin.',
                    'B. Recycling dan recharging pada mesin pendingin',
                    'C. 12 prinsip',
                    'D. 10 prinsip',
                    'E. 13 prinsip',
                ],
                'benar' => 'A'
            ],
            [
                'teks' => 'Mesin 3R dapat melakukan tiga fungsi tersebut secara',
                'opsi' => [
                    'A. Tidak bersamaan dalam satu waktu',
                    'B. Bergantian dalam proses',
                    'C. Bergantian dan berjedah dalam satu proses',
                    'D. Bersamaan dalam satu waktu',
                    'E. Semua jawaban salah',
                ],
                'benar' => 'D'
            ],
            [
                'teks' => 'Recovery pada mesin pendingin yaitu proses menampung',
                'opsi' => [
                    'A. Recovery pada mesin pendingin yaitu proses menampung refrigerant yang ada pada sistem pendingin. Penampungan ini dilakukan karena sistem akan melakukan perawatan atau perbaikan',
                    'B. Recovery pada refrigerasi yaitu proses menampung refrigerant yang ada pada sistem pendingin. Penampungan ini dilakukan karena sistem akan melakukan perawatan atau perbaikan',
                    'C. Recharging pada mesin pendingin yaitu proses menampung refrigerant ...',
                    'D. Pump Down pada system bagian katup ...',
                    'E. Semua jawaban benar',
                ],
                'benar' => 'A'
            ],
            [
                'teks' => 'Unit AC wajib di recovery bila dalam keadaan :',
                'opsi' => [
                    'A. Unit mengalami kerusakan dan memerlukan penggatian part atau pengelasan',
                    'B. Refrigerant tidak dapat di pump down',
                    'C. Refrigerant tidak lancer saat di pump down',
                    'D. Unit mengalami kerusakan',
                    'E. Unit mengalami kerusakan dan memerlukan penggatian part atau pengelasan dan Refrigerant tidak dapat di pump down',
                ],
                'benar' => 'E'
            ],
            [
                'teks' => 'Alat yang dibutuhkan di saat proses Recovery yaitu :',
                'opsi' => [
                    'A. Manifold gauge, Refrigerant Scale, pompa vacuum, recovery tank',
                    'B. Mesin Recovery, Manifold gauge, Refrigerant Scale, pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer',
                    'C. Mesin Recovery, Refrigerant Scale, pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer',
                    'D. Mesin Recovery, pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer',
                    'E. Pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer',
                ],
                'benar' => 'B'
            ],
            [
                'teks' => 'Alat dan bahan di bawah ini adalah digunakan disaat proses:',
                'opsi' => [
                    'A. Proses pump down',
                    'B. Proses Recharging',
                    'C. Proses penggantian part',
                    'D. Proses Recovery Freon',
                    'E. Semua jawaban salah',
                ],
                'benar' => 'D'
            ],
            [
                'teks' => 'Gambar di bawah ini adalah proses Recovery Refigerant yaitu :',
                'opsi' => [
                    'A. Proses Recovery Refigerant',
                    'B. Proses Recovery Refigerant',
                    'C. Proses Recovery Refigerant',
                    'D. Proses Recovery Refigerant',
                    'E. Semua jawaban salah',
                ],
                'benar' => 'A'
            ],
            [
                'teks' => 'Proses langkah kerja mesin 3 R di bawah ini adalah proses :',
                'opsi' => [
                    'A. Pengisian zat pendingin',
                    'B. Evakuasi Udara dan kotoran',
                    'C. Evakuasi Olie pada mesin pendingin',
                    'D. Evakuasi system pendingin',
                    'E. Semua jawaban salah',
                ],
                'benar' => 'A'
            ],
            [
                'teks' => 'Gambar di bawah ini adalah proses :',
                'opsi' => [
                    'A. Vacuum',
                    'B. Charging proses',
                    'C. Vacuum dan Inlet sistem proses',
                    'D. Vacuum dan Oultet Charging proses',
                    'E. Vacuum dan Charging proses',
                ],
                'benar' => 'E'
            ],
            [
                'teks' => 'Proses kerja atau langkah kerja di bawah ini adalah',
                'opsi' => [
                    'A. Vaccum dan Charging Proses',
                    'B. Charging Proses',
                    'C. Vaccum',
                    'D. Proses Reciever',
                    'E. Semua jawaban benar',
                ],
                'benar' => 'A'
            ],
            // 11–20 (jawaban sesuai urutanmu, teks pertanyaan ringkas)
            ['teks' => 'Faktor penyebab AC kotor adalah :', 'opsi' => ['A.','B.','C.','D.','E.'], 'benar' => 'E'],
            ['teks' => 'Pengkondisian udara bertujuan :', 'opsi' => ['A.','B.','C.','D.','E. Semua jawaban salah'], 'benar' => 'D'],
            ['teks' => 'Pemeliharaan AC dibagi berapa proses?', 'opsi' => ['A.','B.','C.','D.','E. Semua jawaban benar'], 'benar' => 'D'],
            ['teks' => 'Proses pada point A.1 adalah :', 'opsi' => ['A.','B.','C.','D.','E.'], 'benar' => 'D'],
            ['teks' => 'Proses sesuai gambar kerja :', 'opsi' => ['A.','B.','C.','D.','E. Semua jawaban salah'], 'benar' => 'A'],
            ['teks' => 'Pemeriksaan perawatan AC dalam gambar adalah :', 'opsi' => ['A. Skala sedang','B. Skala berkala bulanan','C. Skala kecil','D. Skala Pemeliharaan bulanan','E. Semua jawaban salah'], 'benar' => 'D'],
            ['teks' => 'Alat yang masih belum terakomodir adalah :', 'opsi' => ['A. ...','B. ...','C. # Kunci Inggris 6 atau 8 Inch atau Kunci Pas 14 , # Kabel Power Supply, # Kanebo, #Sabut/Busa, #Sabun','D. ...','E. ...'], 'benar' => 'C'],
            ['teks' => 'Alat yang kurang adalah :', 'opsi' => ['A.','B.','C.','D.','E.'], 'benar' => 'E'],
            ['teks' => 'Part indoor no 3 dan 4 adalah :', 'opsi' => ['A. Cross Flow Fan dan selang pembuangan air','B. Cross Flow Fan','C. Selang pembuangan air','D. Cross Flow Fan dan selang pembuangan air out door','E. Fan dan selang pembuangan air'], 'benar' => 'A'],
            ['teks' => 'Part outdoor no 2 dan 3 adalah :', 'opsi' => ['A. Fan dan Cabinet','B. Cabinet','C. Propeller Fan','D. Propeller Fan dan Cabinet outdoor','E. Propeller  dan Cabinet outbow'], 'benar' => 'D'],
        ];

        // 3. Insert pertanyaans & opsi_jawabans
        foreach ($soals as $i => $soal) {
            $pertanyaanId = DB::table('pertanyaans')->insertGetId([
                'tes_id' => $tesId,
                'nomor' => $i + 1,
                'teks_pertanyaan' => $soal['teks'],
                'tipe_jawaban' => 'pilihan_ganda',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($soal['opsi'] as $opsi) {
                $kode = substr($opsi, 0, 1); // ambil huruf A/B/C/D/E
                DB::table('opsi_jawabans')->insert([
                    'pertanyaan_id' => $pertanyaanId,
                    'teks_opsi' => $opsi,
                    'apakah_benar' => $kode === $soal['benar'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // insert juga ke tabel pivot tes_pertanyaan
            DB::table('tes_pertanyaan')->insert([
                'tes_id' => $tesId,
                'pertanyaan_id' => $pertanyaanId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
