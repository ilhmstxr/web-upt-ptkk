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

            // Jawaban pilihan ganda (nullable karena bisa tidak ada)
            $table->foreignId('opsi_jawabans_id')
                ->nullable()
                ->constrained('opsi_jawabans')
                ->onDelete('cascade');

            // Pertanyaan terkait
            $table->foreignId('pertanyaan_id')
                ->constrained('pertanyaans')
                ->onDelete('cascade');

            // Percobaan terkait
            $table->foreignId('percobaan_id')
                ->constrained('percobaans')
                ->onDelete('cascade');

            // Nilai untuk skala likert (1-5), nullable
            $table->tinyInteger('nilai_jawaban')->nullable();

            // Jawaban esai / teks bebas
            $table->text('jawaban_teks')->nullable();

            $table->timestamps();

            // Index untuk performa query filter dan join
            $table->index(['percobaan_id', 'pertanyaan_id']);
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
