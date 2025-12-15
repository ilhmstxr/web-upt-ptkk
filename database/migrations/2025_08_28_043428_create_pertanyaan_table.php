<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertanyaan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tes_id')
                ->constrained('tes')
                ->cascadeOnDelete();

            // ✅ dipakai untuk survei (boleh null untuk pre/post)
            $table->foreignId('kelompok_pertanyaan_id')
                ->nullable()
                ->constrained('kelompok_pertanyaans')
                ->nullOnDelete();

            $table->integer('nomor')->nullable();
            $table->text('teks_pertanyaan');
            $table->string('gambar')->nullable();
            $table->enum('tipe_jawaban', ['pilihan_ganda', 'skala_likert', 'teks_bebas']);

            $table->timestamps();

            // Optional tapi bagus:
            $table->index(['tes_id', 'kelompok_pertanyaan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanyaan');
    }
};
