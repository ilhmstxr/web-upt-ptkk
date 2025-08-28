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
        Schema::create('pertanyaans', function (Blueprint $table) {
            $table->id();

            // pastikan tabel induknya bernama 'kuis' (atau ganti jadi 'kuises' kalau default laravel)
            $table->foreignId('kuis_id')
                ->constrained('kuis') // kalau tabelmu bernama 'kuises', ganti ini
                ->cascadeOnDelete();

            $table->unsignedInteger('nomor');
            $table->text('teks_pertanyaan');
            $table->string('gambar')->nullable();
            $table->enum('tipe_jawaban', ['pilihan_ganda', 'skala_likert'])
                  ->default('pilihan_ganda');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaans');
    }
};
