<?php

namespace App\Console\Commands;

use App\Models\PendaftaranPelatihan;
use App\Models\Peserta;
use App\Traits\ManagesRegistrationTokens;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixOrphanedRegistrations extends Command
{
    // Gunakan Trait yang berisi logika generateToken Anda
    use ManagesRegistrationTokens;

    /**
     * Nama dan signature dari command.
     */
    protected $signature = 'registrations:fix-orphans';

    /**
     * Deskripsi dari command.
     */
    protected $description = 'Mencari peserta tanpa data pendaftaran dan membuatkannya';

    /**
     * Jalankan logic command.
     */
    public function handle()
    {
        $this->info('Mencari peserta yang tidak memiliki data pendaftaran...');

        // Cara Eloquent untuk "LEFT JOIN ... WHERE IS NULL"
        $orphanedPeserta = Peserta::whereDoesntHave('pendaftaranPelatihan')->get();

        if ($orphanedPeserta->isEmpty()) {
            $this->info('✅ Tidak ada peserta bermasalah yang ditemukan.');
            return 0;
        }

        $this->warn("Ditemukan " . $orphanedPeserta->count() . " peserta yang bermasalah.");

        // Minta konfirmasi sebelum melakukan perubahan
        if (!$this->confirm('Apakah Anda ingin membuatkan data pendaftaran untuk peserta ini?')) {
            $this->info('Operasi dibatalkan.');
            return 1;
        }

        DB::beginTransaction();
        try {
            foreach ($orphanedPeserta as $peserta) {
                $this->line("Memproses Peserta ID: {$peserta->id} - {$peserta->nama}");

                // Panggil fungsi generateToken dari Trait
                ['nomor' => $nomorReg, 'urutan' => $urutKompetensi] = $this->generateToken(
                    $peserta->pelatihan_id,
                    $peserta->kompetensi_id
                );

                // Buat record pendaftaran_pelatihan yang hilang
                PendaftaranPelatihan::create([
                    'peserta_id'            => $peserta->id,
                    'pelatihan_id'          => $peserta->pelatihan_id,
                    // 'kompetensi_id'          => $peserta->kompetensi_id, // (Opsional) jika ada di tabel pendaftaran
                    // 'urutan_per_kompetensi'  => $urutKompetensi,      // (Opsional) jika ada di tabel pendaftaran
                    'nomor_registrasi'      => $nomorReg,
                    'tanggal_pendaftaran'   => $peserta->created_at ?? now(), // Gunakan tanggal dibuatnya peserta
                ]);

                $this->info("-> Berhasil membuat pendaftaran dengan No. Reg: {$nomorReg}");
            }

            DB::commit();
            $this->info('✅ Semua peserta bermasalah telah diperbaiki.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Terjadi kesalahan! Semua perubahan telah dibatalkan.');
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}