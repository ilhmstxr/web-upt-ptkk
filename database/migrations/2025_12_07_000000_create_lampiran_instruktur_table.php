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
        Schema::create('lampiran_instruktur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instruktur_id')->constrained('instruktur')->cascadeOnDelete();
            // Columns are text/string to store links as requested
            $table->text('cv')->nullable();
            $table->text('ktp')->nullable();
            $table->text('ijazah')->nullable();
            $table->text('sertifikat_kompetensi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran_instruktur');
    }
};
