<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tes;
use App\Models\Pertanyaan;
use App\Models\Pelatihan;
use Illuminate\Support\Facades\DB;

class CloneMonevTemplate extends Command
{
    protected $signature = 'monev:clone {pelatihan_id : ID Pelatihan untuk tes baru} {judul_baru? : Judul Tes Baru (Opsional)}';
    protected $description = 'Clone pertanyaan dari MASTER TEMPLATE MONEV ke Tes Baru';

    public function handle()
    {
        $pelatihanId = $this->argument('pelatihan_id');
        $judulBaru = $this->argument('judul_baru');

        // 1. Validasi Pelatihan
        $pelatihan = Pelatihan::find($pelatihanId);
        if (!$pelatihan) {
            $this->error("Pelatihan dengan ID $pelatihanId tidak ditemukan.");
            return 1;
        }

        // 2. Ambil Template
        $template = Tes::where('judul', 'MASTER TEMPLATE MONEV')->with('pertanyaan')->first();
        if (!$template) {
            $this->error("MASTER TEMPLATE MONEV tidak ditemukan. Harap jalankan 'php artisan db:seed --class=MonevTemplateSeeder' terlebih dahulu.");
            return 1;
        }

        // 3. Tentukan Judul
        if (!$judulBaru) {
            // Auto-generate judul: "Monev Pelatihan X"
            $judulBaru = "Monev - " . $pelatihan->nama;
        }

        DB::beginTransaction();
        try {
            // 4. Buat Tes Baru
            $tesBaru = Tes::create([
                'judul' => $judulBaru,
                'deskripsi' => 'Hasil clone dari Master Template Monev',
                'tipe' => 'survei',
                'pelatihan_id' => $pelatihan->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addDays(3), // Default 3 hari
            ]);

            $this->info("Created Tes: {$tesBaru->judul} (ID: {$tesBaru->id})");

            // 5. Clone Pertanyaan
            $count = 0;
            foreach ($template->pertanyaan as $p) {
                Pertanyaan::create([
                    'tes_id' => $tesBaru->id,
                    'nomor' => $p->nomor,
                    'kategori' => $p->kategori, // Duplicate kategori
                    'teks_pertanyaan' => $p->teks_pertanyaan,
                    'tipe_jawaban' => $p->tipe_jawaban,
                    'gambar' => $p->gambar,
                ]);
                $count++;
            }

            DB::commit();
            $this->info("Berhasil meng-clone $count pertanyaan ke Tes baru.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Gagal meng-clone: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
