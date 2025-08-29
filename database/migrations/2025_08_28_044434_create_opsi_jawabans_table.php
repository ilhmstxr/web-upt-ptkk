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
        Schema::create('opsi_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertanyaan_id')->constrained('pertanyaans')->onDelete('cascade');
<<<<<<< HEAD:database/migrations/2025_08_28_043434_create_opsi_jawabans_table.php
=======
            $table->foreignId('percobaan_id')->constrained('percobaans')->onDelete('cascade');
            $table->integer('nilai_jawaban')->nullable(); // Untuk skala likert (1-5)
>>>>>>> merge-test-monev:database/migrations/2025_08_28_044434_create_opsi_jawabans_table.php
            $table->text('teks_opsi')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('apakah_benar')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opsi_jawabans');
    }
};