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

            $table->foreignId('opsi_jawaban_id')
                ->nullable()
                ->constrained('opsi_jawabans')
                ->cascadeOnDelete();

            $table->foreignId('pertanyaan_id')
                ->constrained('pertanyaans')
                ->cascadeOnDelete();

            $table->foreignId('percobaan_id')
                ->constrained('percobaans')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('nilai_jawaban')->nullable(); 
            // untuk skala likert (1-5). 
            // Bisa ditambah constraint kalau mau validasi di DB:
            // ->checkBetween(1,5) kalau pakai Laravel 11+

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
