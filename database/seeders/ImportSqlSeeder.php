<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class ImportSqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Sesuaikan path sesuai lokasi file yang nyata
        $sqlFilePath = database_path('backupData\insert-data-berkala.sql');

        if (!File::exists($sqlFilePath)) {
            $this->command->error("File tidak ditemukan: {$sqlFilePath}");
            return;
        }

        $exitCode = Artisan::call('import:sql', [
            'path' => $sqlFilePath,
            // '--connection' => 'mysql', // opsional bila butuh koneksi tertentu
        ]);

        // Tampilkan output dari command agar error tidak silent
        $this->command->line(Artisan::output());

        if ($exitCode !== 0) {
            $this->command->error("Import gagal. Exit code: {$exitCode}");
            return;
        }

        $this->command->info('Import selesai.');
    
    }
}
