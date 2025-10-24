<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDOException;

class ImportSqlFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'import:sql {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import an SQL file by sorting inserts based on table dependencies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sqlFilePath = $this->argument('path');

        if (!File::exists($sqlFilePath)) {
            $this->error("File not found at: {$sqlFilePath}");
            return 1;
        }

        // 1. Tentukan urutan eksekusi tabel yang benar
        $tableOrder = [
            'bidang',
            'cabang_dinas',
            'instansi',
            'pelatihan',
            'tes',
            'pertanyaan',
            'opsi_jawaban',
            'pivot_jawaban',
            'peserta_survei',
            'users',
            'peserta',
            'lampiran',
            'percobaan',
            'jawaban_user',
            'bidang_pelatihan',
            'pendaftaran_pelatihan',
        ];

        try {
            $this->info('Reading and parsing SQL file...');
            $groupedInserts = [];

            $sqlContent = File::get($sqlFilePath);

            // --- PERUBAHAN UTAMA ADA DI SINI ---
            // Menggunakan preg_split untuk memecah query dengan lebih cerdas
            $queries = preg_split('/;\s*(\r\n|\n|\r)/', $sqlContent);

            foreach ($queries as $query) {
                $query = trim($query);

                if (empty($query)) {
                    continue;
                }

                if (preg_match('/INSERT INTO `?(\w+)`?/', $query, $matches)) {
                    $tableName = $matches[1];
                    $groupedInserts[$tableName][] = $query;
                }
            }

            DB::transaction(function () use ($tableOrder, $groupedInserts) {
                $this->info('Starting database import...');

                foreach ($tableOrder as $tableName) {
                    if (isset($groupedInserts[$tableName])) {
                        $this->line(" - Inserting data for table: {$tableName}");
                        foreach ($groupedInserts[$tableName] as $insertQuery) {
                            DB::statement($insertQuery . ';');
                        }
                    }
                }
            });

            $this->info('🎉 SQL file imported successfully!');
        } catch (\Exception $e) {
            $this->error("Database error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
