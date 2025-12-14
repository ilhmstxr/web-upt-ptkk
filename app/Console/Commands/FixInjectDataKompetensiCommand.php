<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InjectData;
use App\Models\KompetensiPelatihan;
use Illuminate\Support\Facades\DB;

class FixInjectDataKompetensiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:inject-data-kompetensi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates InjectData kompetensi_id to refer to KompetensiPelatihan ID instead of Kompetensi ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting InjectData kompetensi_id fix...');

        // We assume InjectData has 'pelatihan_id' and 'kompetensi_id' fields.
        // If the table was just imported from SQL, it might have raw integer values in 'kompetensi_id' 
        // that correspond to the 'kompetensi' table IDs.

        $items = InjectData::whereNotNull('pelatihan_id')
            ->whereNotNull('kompetensi_id')
            ->get();

        $count = 0;
        $errors = 0;

        $bar = $this->output->createProgressBar($items->count());
        $bar->start();

        foreach ($items as $item) {
            // Find KompetensiPelatihan matching pelatihan_id and kompetensi_id 
            // where item->kompetensi_id is interpreted as the raw kompetensi ID (from the kompetensi table)

            $kp = KompetensiPelatihan::where('pelatihan_id', $item->pelatihan_id)
                ->where('kompetensi_id', $item->kompetensi_id)
                ->first();

            if ($kp) {
                // Only update if different to avoid redundant updates
                // We are updating the SAME column: kompetensi_id
                // So if it's already updated to $kp->id, we skip.
                if ($item->kompetensi_id != $kp->id) {
                    $item->kompetensi_id = $kp->id;
                    $item->save();
                    $count++;
                }
            } else {
                // If not found, check if it's already updated (i.e., item->kompetensi_id IS ALREADY a KP id)
                $existingKp = KompetensiPelatihan::find($item->kompetensi_id);
                if ($existingKp && $existingKp->pelatihan_id == $item->pelatihan_id) {
                    // It seems already correct.
                } else {
                    $errors++;
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Updated {$count} records. Encountered {$errors} potential issues (or already updated items).");
    }
}
