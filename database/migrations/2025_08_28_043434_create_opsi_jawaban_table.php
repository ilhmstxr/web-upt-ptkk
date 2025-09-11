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
        Schema::create('opsi_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan')->onDelete('cascade');
            $table->text('teks_opsi');
            $table->string('gambar')->nullable();
            $table->boolean('apakah_benar')->default(false);
            // IMPROVE: tambahkan index untuk pertanyaan_id dan apakah_benar
            // $table->index(['pertanyaan_id', 'apakah_benar']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opsi_jawaban');
    }
};
