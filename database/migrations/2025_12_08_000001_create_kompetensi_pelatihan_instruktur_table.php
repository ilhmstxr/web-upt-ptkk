<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kompetensi_pelatihan_instruktur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kompetensi_pelatihan_id')->constrained('kompetensi_pelatihan')->cascadeOnDelete();
            $table->foreignId('instruktur_id')->constrained('instruktur')->cascadeOnDelete();
            $table->unique(['kompetensi_pelatihan_id', 'instruktur_id'], 'kp_instruktur_unique');
            $table->timestamps();
        });


        // Migrate existing data
        $existing = DB::table('kompetensi_pelatihan')
            ->whereNotNull('instruktur_id')
            ->get();

        foreach ($existing as $item) {
            DB::table('kompetensi_pelatihan_instruktur')->insert([
                'kompetensi_pelatihan_id' => $item->id,
                'instruktur_id' => $item->instruktur_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop the old column
        Schema::table('kompetensi_pelatihan', function (Blueprint $table) {
            $table->dropForeign(['instruktur_id']);
            $table->dropColumn('instruktur_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore column
        Schema::table('kompetensi_pelatihan', function (Blueprint $table) {
            $table->foreignId('instruktur_id')->nullable()->constrained('instruktur')->nullOnDelete();
        });

        // Restore data (take first instructor found)
        $pivots = DB::table('kompetensi_pelatihan_instruktur')->get();
        foreach ($pivots as $pivot) {
            // We can only restore one, so we just overwrite if multiple exist
            DB::table('kompetensi_pelatihan')
                ->where('id', $pivot->kompetensi_pelatihan_id)
                ->update(['instruktur_id' => $pivot->instruktur_id]);
        }

        Schema::dropIfExists('kompetensi_pelatihan_instruktur');
    }
};
