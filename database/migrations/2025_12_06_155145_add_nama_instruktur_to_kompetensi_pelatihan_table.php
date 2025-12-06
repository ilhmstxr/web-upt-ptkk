<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kompetensi_pelatihan', function (Blueprint $table) {
            $table->string('nama_instruktur')->nullable()->after('instruktur_id');
            // Make instruktur_id nullable if it's not already
            $table->unsignedBigInteger('instruktur_id')->nullable()->change();
        });

        // Migrate existing data
        \Illuminate\Support\Facades\DB::table('kompetensi_pelatihan')
            ->whereNotNull('instruktur_id')
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    $instruktur = \Illuminate\Support\Facades\DB::table('instruktur')->find($row->instruktur_id);
                    if ($instruktur) {
                        \Illuminate\Support\Facades\DB::table('kompetensi_pelatihan')
                            ->where('id', $row->id)
                            ->update(['nama_instruktur' => $instruktur->nama]);
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kompetensi_pelatihan', function (Blueprint $table) {
            $table->dropColumn('nama_instruktur');
        });
    }
};
