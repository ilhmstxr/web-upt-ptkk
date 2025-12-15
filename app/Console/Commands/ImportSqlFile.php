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
        $tableOrder1 = [
            'kompetensi',
            'cabang_dinas',
            'instansi',
            'pelatihan',
            'kompetensi_pelatihan',
            'tes',
            'pertanyaan',
            'opsi_jawaban',
            'pivot_jawaban',
            'peserta_survei',
            'users',
            'peserta',
            'lampiran_peserta',
            'percobaan',
            'jawaban_user',
            'pendaftaran_pelatihan',
            'instruktur'
        ];

        $tableOrder2 = [
            'inject_data',
            'inject_data_details'
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


            // --- LOGIKA PEMILIHAN tableOrder ADA DI SINI ---

            // 1. Dapatkan semua nama tabel yang ADA di file SQL
            $foundTablesInSql = array_keys($groupedInserts);

            // 2. Cek apakah ada tabel dari $tableOrder1 yang ditemukan di file SQL
            $intersection = array_intersect($tableOrder1, $foundTablesInSql);

            if (!empty($intersection)) {
                // 3. JIKA DITEMUKAN: Kita gunakan $tableOrder1 DITAMBAH $tableOrder2
                $this->info('Main tables (Order 1) detected. Using Main Order + Inject Order.');
                $tableOrder = array_merge($tableOrder1, $tableOrder2);
            } else {
                // 4. JIKA TIDAK: Kita asumsikan ini adalah $tableOrder2
                $this->info('Main tables not found. Using Inject Order (Order 2).');
                $tableOrder = $tableOrder2;
            }

            // --- AKHIR DARI LOGIKA PEMILIHAN ---


            DB::transaction(function () use ($tableOrder, $groupedInserts) {
                $this->info('Starting database import...');
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                // $tableOrder sekarang berisi $tableOrder1 atau $tableOrder2
                // sesuai hasil deteksi di atas.
                foreach ($tableOrder as $tableName) {
                    if (isset($groupedInserts[$tableName])) {
                        $this->line(" - Inserting data for table: {$tableName}");
                        foreach ($groupedInserts[$tableName] as $insertQuery) {
                            DB::statement($insertQuery . ';');
                        }
                    }
                }
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            });

            $this->info('🎉 SQL file imported successfully!');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if (strlen($msg) > 500) {
                $msg = substr($msg, 0, 500) . '... [Truncated]';
            }
            $this->error("Database error: " . $msg);
            return 1;
        }

        return 0;
    }
}
