<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportMonevLegacy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:monev-legacy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate SQL insert script from legacy Monev data with batch inserts and explicit IDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Monev Legacy Import (Batch Mode)...');

        $outDir = base_path('dokumen');
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true);
        }
        $outFile = $outDir . '/insert-data-monev-fix.sql';

        // Initialize ID Counters (Safe ranges to avoid collision)
        $idPertanyaan = 10001;
        $idOpsi = 10001;
        $idPercobaan = 10001;
        $idJawabanUser = 30001;

        // Data Storage
        $batchPertanyaan = [];
        $batchOpsi = [];
        $batchPercobaan = [];
        $batchJawabanUser = [];

        // Maps for lookup
        // Key: "p{pelatihan_id}_t{tes_id}_c{col_num}" => pertanyaan_id
        $mapPertanyaan = [];

        // Key: "q{pertanyaan_id}_{teks_opsi}" => opsi_id
        $mapOpsi = [];

        // 1. Process Questions
        // --------------------
        $rawQuestions = DB::select("SELECT * FROM `data-mentah-monev`");
        $processedKeys = [];

        foreach ($rawQuestions as $row) {
            $pId = $row->pelatihan_id;
            $tId = $row->tes_id ?? 0;
            $type = $row->tipe;

            $key = "{$pId}_{$tId}";

            if ($type === 'pertanyaan') {
                if (isset($processedKeys[$key])) continue;
                $processedKeys[$key] = true;

                $this->info("Processing Questionnaire for Pelatihan $pId, Tes $tId");

                // Get options rows
                $optionRows = DB::select("
                    SELECT * FROM `data-mentah-monev` 
                    WHERE tipe='jawaban' AND pelatihan_id=? AND tes_id=?
                ", [$pId, $tId]);

                // Reset Category State for each Questionnaire
                $currentCategory = 'pelayanan';
                $pkCount = 0;

                // Loop columns 1 to 37
                for ($i = 1; $i <= 37; $i++) {
                    $colName = "pertanyaan $i";
                    // Handle potential property access issues if any
                    $val = $row->{$colName} ?? null;

                    if (!empty($val)) {
                        $currentQId = $idPertanyaan++;
                        $teksPertanyaan = addslashes($val);

                        // Default to skala_likert (User Request)
                        $tipeJawaban = 'skala_likert';

                        // Check for Pesan / Kesan
                        if (stripos($val, 'Pesan') !== false || stripos($val, 'Kesan') !== false) {
                            $tipeJawaban = 'teks_bebas';

                            // Category Switch Logic
                            // If it's the 1st PK, it ends 'pelayanan', next is 'fasilitas'
                            // If it's the 2nd PK, it ends 'fasilitas', next is 'instruktur'

                            // Assign current category to this PK question
                            $assignedCategory = $currentCategory;

                            $pkCount++;
                            if ($pkCount == 1) {
                                $currentCategory = 'fasilitas';
                            } elseif ($pkCount >= 2) {
                                $currentCategory = 'instruktur';
                            }
                        } else {
                            // Normal question
                            $assignedCategory = $currentCategory;
                        }

                        // Collect Options
                        $validOptions = [];
                        foreach ($optionRows as $optRow) {
                            $optText = $optRow->{$colName} ?? null;
                            if (!empty($optText)) {
                                $validOptions[] = $optText;
                            }
                        }
                        $validOptions = array_unique($validOptions);

                        if (empty($validOptions)) {
                            $tipeJawaban = 'teks_bebas';
                        }


                        // Add to Pertanyaan Batch
                        $batchPertanyaan[] = "($currentQId, $tId, $i, '$teksPertanyaan', '$assignedCategory', '$tipeJawaban', NOW(), NOW())";

                        // Map Question
                        $mapPertanyaan["p{$pId}_t{$tId}_c{$i}"] = $currentQId;

                        // Add Options to Batch
                        foreach ($validOptions as $opt) {
                            $currentOpsiId = $idOpsi++;
                            $teksOpsi = addslashes($opt);

                            $batchOpsi[] = "($currentOpsiId, $currentQId, '$teksOpsi', NOW(), NOW())";

                            // Map Option (use MD5 for text key if too long, but simple concat usually OK)
                            // We use a safe key format
                            $mapOpsi["q{$currentQId}_" . md5($opt)] = $currentOpsiId;
                        }
                    }
                }
            }
        }


        // 2. Process User Answers
        // -----------------------
        $this->info("Processing User Answers...");
        $userAnswers = DB::select("SELECT * FROM `data-jawaban-user`");

        foreach ($userAnswers as $row) {
            $pId = $row->pelatihan_id;
            $tId = $row->tes_id;
            $pesertaId = $row->peserta_id;

            $currentPercobaanId = $idPercobaan++;

            // Add Percobaan Batch
            $batchPercobaan[] = "($currentPercobaanId, $pesertaId, $pId, $tId, NOW(), NOW(), NOW(), NOW())";

            // Loop Answers 1..37
            for ($i = 1; $i <= 37; $i++) {
                $ansCol = "jawaban_$i";
                $ansText = $row->{$ansCol} ?? null;

                $qKey = "p{$pId}_t{$tId}_c{$i}";

                // If we have an answer AND we know the question ID
                if (!empty($ansText) && isset($mapPertanyaan[$qKey])) {
                    $qId = $mapPertanyaan[$qKey];
                    $currentAnsUserId = $idJawabanUser++;

                    $opsiIdVal = 'NULL';
                    $teksVal = 'NULL';

                    // Check if answer maps to an option ID
                    $optKey = "q{$qId}_" . md5($ansText);

                    if (isset($mapOpsi[$optKey])) {
                        $opsiIdVal = $mapOpsi[$optKey];
                    } else {
                        // Free text answer
                        $teksVal = "'" . addslashes($ansText) . "'";
                    }

                    $batchJawabanUser[] = "($currentAnsUserId, $currentPercobaanId, $qId, $opsiIdVal, $teksVal, NOW(), NOW())";
                }
            }
        }

        // 3. Write to File
        // ----------------
        $this->info("Writing SQL file...");

        $content = "START TRANSACTION;\n\n";

        // Write Pertanyaan
        if (!empty($batchPertanyaan)) {
            $content .= "-- BATCH: PERTANYAAN\n";
            $chunks = array_chunk($batchPertanyaan, 50);
            foreach ($chunks as $chunk) {
                $content .= "INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, kategori, tipe_jawaban, created_at, updated_at) VALUES \n";
                $content .= implode(",\n", $chunk) . ";\n\n";
            }
        }

        // Write Opsi Jawaban
        if (!empty($batchOpsi)) {
            $content .= "-- BATCH: OPSI JAWABAN\n";
            $chunks = array_chunk($batchOpsi, 50);
            foreach ($chunks as $chunk) {
                $content .= "INSERT INTO opsi_jawaban (id, pertanyaan_id, teks_opsi, created_at, updated_at) VALUES \n";
                $content .= implode(",\n", $chunk) . ";\n\n";
            }
        }

        // Write Percobaan
        if (!empty($batchPercobaan)) {
            $content .= "-- BATCH: PERCOBAAN\n";
            $chunks = array_chunk($batchPercobaan, 50);
            foreach ($chunks as $chunk) {
                $content .= "INSERT INTO percobaan (id, peserta_id, pelatihan_id, tes_id, waktu_mulai, waktu_selesai, created_at, updated_at) VALUES \n";
                $content .= implode(",\n", $chunk) . ";\n\n";
            }
        }

        // Write Jawaban User
        if (!empty($batchJawabanUser)) {
            $content .= "-- BATCH: JAWABAN USER\n";
            $chunks = array_chunk($batchJawabanUser, 50);
            foreach ($chunks as $chunk) {
                $content .= "INSERT INTO jawaban_user (id, percobaan_id, pertanyaan_id, opsi_jawaban_id, jawaban_teks, created_at, updated_at) VALUES \n";
                $content .= implode(",\n", $chunk) . ";\n\n";
            }
        }

        $content .= "COMMIT;\n";

        File::put($outFile, $content);
        $this->info("Success! Generated batch INSERT SQL at: $outFile");
    }
}
