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
        Schema::create('statistik_kompetensi', function (Blueprint $table) {
            $table->id();

            // contoh: MJC_ANGKATAN_I_2025, MTU_ANGKATAN_II_2025
            $table->string('batch', 150);

            // contoh: PROGRAM AKSELERASI KELAS MJC GURU ANGKATAN I TAHUN 2025
            $table->string('nama_program', 255);

            // contoh: DESAIN GRAFIS (LOGO DAN PACKAGING)
            $table->string('kompetensi_keahlian', 255);

            $table->decimal('pre_avg', 6, 2);
            $table->decimal('post_avg', 6, 2);
            $table->decimal('praktek_avg', 6, 2);
            $table->decimal('rata_kelas', 6, 2);

            $table->timestamps();

            // biar tidak dobel
            $table->unique(
                ['batch', 'kompetensi_keahlian'],
                'stat_kompetensi_batch_unique'
            );
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistik_kompetensi');
    }
};
