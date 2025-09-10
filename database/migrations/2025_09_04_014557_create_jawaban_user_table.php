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
        Schema::create('jawaban_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opsi_jawaban_id')->nullable()->constrained('opsi_jawaban')->nullOnDelete(); // untuk jawaban pilihan ganda
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan')->cascadeOnDelete();    // pertanyaan terkait
            $table->foreignId('percobaan_id')->constrained('percobaan')->cascadeOnDelete();     // percobaan terkait (pre/post test)
            $table->String('nilai_jawaban')->nullable();    // untuk skala likert 1-5
            $table->text('jawaban_teks')->nullable();     // untuk jawaban es
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_user');
    }
};
