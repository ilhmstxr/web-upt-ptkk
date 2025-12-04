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
        Schema::create('pertanyaan', function (Blueprint $table) {
            $table->id();
            // IMPROVE: tes_id seharusnya tidak perlu, karena sudah ada di tabel pivot tes_pertanyaan
            $table->foreignId('tes_id')->constrained('tes')->onDelete('cascade');
            $table->integer('nomor');
            $table->text('teks_pertanyaan');
            $table->string('kategori')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('tipe_jawaban', ['pilihan_ganda', 'skala_likert', 'teks_bebas']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaan');
    }
};