<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kompetensi;
use App\Models\Pelatihan;
use App\Models\Instansi;
use App\Models\Peserta;
use App\Models\Lampiran;
use Illuminate\Support\Carbon;

class PelatihanSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi database.
     */
    public function run(): void
    {
        // --- 1. BUAT DATA INSTANSI (INDEPENDEN) ---
        // $smkn1_sby = Instansi::create([
        //     'asal_instansi' => 'SMKN 1 Surabaya',
        //     'alamat_instansi' => 'Jl. Smea No.4, Wonokromo, Surabaya',
        //     'kompetensi_keahlian' => 'Teknik Komputer dan Jaringan',
        //     'kelas' => 'XII TKJ 1',
        //     'cabang_dinas_wilayah' => 'Wilayah Surabaya',
        // ]);

        // $smkn3_mlg = Instansi::create([
        //     'asal_instansi' => 'SMKN 3 Malang',
        //     'alamat_instansi' => 'Jl. Surabaya No.1, Klojen, Malang',
        //     'kompetensi_keahlian' => 'Tata Busana',
        //     'kelas' => 'XII Busana 2',
        //     'cabang_dinas_wilayah' => 'Wilayah Malang',
        // ]);

        // --- 2. BUAT DATA BIDANG (INDEPENDEN) ---


        // --- 4. BUAT DATA PESERTA & LAMPIRANNYA (TERHUBUNG KE PELATIHAN & INSTANSI) ---
        // $peserta_andi = Peserta::create([
        //     'pelatihan_id' => $pelatihan1->id, // Andi ikut pelatihan kue
        //     'instansi_id' => $smkn1_sby->id,
        //     'nama' => 'Andi Wijaya',
        //     'nik' => '3501011011900001',
        //     'tempat_lahir' => 'Surabaya',
        //     'tanggal_lahir' => '1990-11-10',
        //     'jenis_kelamin' => 'Laki-laki',
        //     'agama' => 'Islam',
        //     'alamat' => 'Jl. Pahlawan No. 45, Surabaya',
        //     'no_hp' => '081222333444',
        //     'email' => 'andi.wijaya@example.com',
        // ]);

        // Lampiran::create([
        //     'peserta_id' => $peserta_andi->id,
        //     'no_surat_tugas' => '421.5/001/SMKN1-SBY/2025',
        //     'pas_foto' => 'berkas_pendaftaran/default.jpg',
        //     'fc_ktp' => 'berkas_pendaftaran/default.pdf',
        //     'fc_ijazah' => 'berkas_pendaftaran/default.pdf',
        //     'fc_surat_tugas' => 'berkas_pendaftaran/default.pdf',
        //     'fc_surat_sehat' => 'berkas_pendaftaran/default.pdf',
        // ]);

        // $peserta_bunga = Peserta::create([
        //     'pelatihan_id' => $pelatihan2->id, // Bunga ikut pelatihan gaun
        //     'instansi_id' => $smkn3_mlg->id,
        //     'nama' => 'Bunga Citra Lestari',
        //     'nik' => '3501012012910002',
        //     'tempat_lahir' => 'Malang',
        //     'tanggal_lahir' => '1991-12-20',
        //     'jenis_kelamin' => 'Perempuan',
        //     'agama' => 'Islam',
        //     'alamat' => 'Jl. Ijen No. 10, Malang',
        //     'no_hp' => '081333444555',
        //     'email' => 'bunga.citra@example.com',
        // ]);

        // Lampiran::create([
        //     'peserta_id' => $peserta_bunga->id,
        //     'no_surat_tugas' => null,
        //     'pas_foto' => 'berkas_pendaftaran/default.jpg',
        //     'fc_ktp' => 'berkas_pendaftaran/default.pdf',
        //     'fc_ijazah' => 'berkas_pendaftaran/default.pdf',
        //     'fc_surat_tugas' => null,
        //     'fc_surat_sehat' => 'berkas_pendaftaran/default.pdf',
        // ]);
        // --- LANGKAH 2: BUAT DATA PELATIHAN UNTUK SETIAP BIDANG YANG BARU DIBUAT ---
        $pelatihan = [[
            'nama_pelatihan' => 'Kegiatan Pengembangan dan Pelatihan Kompetensi Vokasi bagi Siswa SMA/SMK (MILEA) menuju Generasi Emas 2045 (Kelas Keterampilan) Angkatan II Tahun 2025', // Ambil ID dari model yang baru saja dibuat
            'tanggal_mulai' => Carbon::parse('2025-09-01'),
            'tanggal_selesai' => Carbon::parse('2025-09-06'),
        ],];
        Pelatihan::insert($pelatihan);
    }
}
