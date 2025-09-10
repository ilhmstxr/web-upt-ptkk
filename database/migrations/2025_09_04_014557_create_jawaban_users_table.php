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
        Schema::create('jawaban_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opsi_jawabans_id')->nullable()->constrained('opsi_jawabans')->nullOnDelete(); // untuk jawaban pilihan ganda
            $table->foreignId('pertanyaan_id')->constrained('pertanyaans')->cascadeOnDelete();    // pertanyaan terkait
            $table->foreignId('percobaan_id')->constrained('percobaans')->cascadeOnDelete();     // percobaan terkait (pre/post test)
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
        Schema::dropIfExists('jawaban_users');
    }
};
