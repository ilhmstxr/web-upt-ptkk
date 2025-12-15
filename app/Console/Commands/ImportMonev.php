<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tes;
use App\Models\Pertanyaan;
use App\Models\OpsiJawaban;
use App\Models\Pelatihan;

class ImportMonev extends Command
{
    protected $signature = 'import:monev';
    protected $description = 'Import Monev data from backupData/cek-monev.sql with deduplication';

    public function handle()
    {
        $filePath = base_path('backupData/cek-monev.sql');
        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return 1;
        }

        $pelatihanId = Pelatihan::latest()->value('id');
        if (!$pelatihanId) {
            $this->error("No Pelatihan found. Cannot create Tes.");
            return 1;
        }

        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);

        $currentTes = null;
        $currentPertanyaans = []; // [index => PertanyaanModel]

        $this->info("Starting import...");

        // Reset processed cache/state if needed, but we rely on DB checks.

        DB::beginTransaction();
        try {
            foreach ($lines as $line) {
                $line = trim($line);

                // Skip empty or non-data lines (simple heuristic)
                // We typically look for lines starting with '(' and ending with '),' or ');'
                // OR lines starting with 'INSERT INTO' for the old format (though user changed to bulk).
                // The bulk format in diff:
                // VALUES
                //     ('...'),
                //     ('...');

                if (!str_starts_with($line, '(')) {
                    // Check if it's the old single-line INSERT format just in case
                    if (str_starts_with($line, 'INSERT INTO') && str_contains($line, 'VALUES (')) {
                        $startPos = strpos($line, 'VALUES (');
                        $line = substr($line, $startPos + 7); // keep '(...'
                    } else {
                        continue;
                    }
                }

                // Clean trailing punctuation to get purely (...)
                // Remove trailing ';' or ','
                $dataStr = rtrim($line, ';');
                $dataStr = rtrim($dataStr, ',');

                // Ensure wrapped in ()
                if (!str_starts_with($dataStr, '(') || !str_ends_with($dataStr, ')')) {
                    continue;
                }

                // Strip outer parens for str_getcsv
                $inner = substr($dataStr, 1, -1);

                // Parse CSV
                // Note: str_getcsv default quote is ", sql uses ' usually.
                // The user's file uses single quotes: 'pertanyaan tes a', ...
                $row = str_getcsv($inner, ',', "'");

                if (count($row) < 2) continue;

                $keterangan = $row[0] ?? '';
                $columns = array_slice($row, 1);

                if (str_starts_with($keterangan, 'pertanyaan tes')) {
                    // This row defines the Questions

                    // Specific Rule: Check Question 1 (index 0 in columns)
                    $q1Text = $columns[0] ?? '';

                    // Check if a Tes with this Q1 already exists for this Pelatihan
                    $existingTes = Tes::where('pelatihan_id', $pelatihanId)
                        ->whereHas('pertanyaan', function ($q) use ($q1Text) {
                            $q->where('nomor', 1)
                                ->where('teks_pertanyaan', $q1Text);
                        })
                        ->with('pertanyaan')
                        ->first();

                    if ($existingTes) {
                        $this->info("Found existing Tes matching Question 1. Reusing Tes ID: {$existingTes->id} for '$keterangan'");
                        $currentTes = $existingTes;

                        // Load existing questions map
                        $currentPertanyaans = [];
                        foreach ($existingTes->pertanyaan as $p) {
                            $currentPertanyaans[$p->nomor] = $p;
                        }
                    } else {
                        // Create New
                        $parts = explode(' ', $keterangan);
                        $letter = end($parts);
                        $judul = 'Monev Tes ' . strtoupper($letter);

                        $this->info("Creating New Tes: $judul");

                        $currentTes = Tes::create([
                            'judul' => $judul,
                            'tipe' => 'survei',
                            'pelatihan_id' => $pelatihanId,
                        ]);

                        $currentPertanyaans = [];
                        foreach ($columns as $index => $text) {
                            $idx = $index + 1;
                            if ($text === '') continue; // Skip empty columns

                            $tipeJawaban = 'skala_likert';
                            if (stripos($text, 'Intruktur terfavorit') !== false || stripos($text, 'Pesan dan Kesan') !== false) {
                                $tipeJawaban = 'teks_bebas';
                            }

                            $pertanyaan = Pertanyaan::create([
                                'tes_id' => $currentTes->id,
                                'nomor' => $idx,
                                'teks_pertanyaan' => $text,
                                'tipe_jawaban' => $tipeJawaban,
                            ]);
                            $currentPertanyaans[$idx] = $pertanyaan;
                        }
                    }
                } elseif (str_starts_with($keterangan, 'jawaban')) {
                    // Process Answers (Options)
                    if (!$currentTes) continue;

                    foreach ($columns as $index => $text) {
                        $idx = $index + 1;
                        // Avoid adding empty options
                        if ($text === '') continue;

                        if (!isset($currentPertanyaans[$idx])) {
                            // Question might not exist (e.g. columns 36/37 in some tests)
                            continue;
                        }

                        $pertanyaan = $currentPertanyaans[$idx];

                        // Deduplicate option: check if this text already exists for this question
                        $exists = OpsiJawaban::where('pertanyaan_id', $pertanyaan->id)
                            ->where('teks_opsi', $text)
                            ->exists();

                        if (!$exists) {
                            OpsiJawaban::create([
                                'pertanyaan_id' => $pertanyaan->id,
                                'teks_opsi' => $text,
                                'apakah_benar' => false,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            $this->info("Import process completed.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
