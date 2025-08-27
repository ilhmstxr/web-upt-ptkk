<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')
                  ->constrained('pelatihans')
                  ->onDelete('cascade'); // hapus pre_test jika pelatihan dihapus
            $table->foreignId('bidang_id')
                  ->nullable()
                  ->constrained('bidangs')
                  ->onDelete('set null'); // relasi ke tabel Bidang
            $table->integer('nomor');      // nomor pertanyaan
            $table->text('pertanyaan');    // pertanyaan
            $table->string('option_a');    // opsi A
            $table->string('option_b');    // opsi B
            $table->string('option_c');    // opsi C
            $table->string('option_d');    // opsi D
            $table->enum('correct_answer', ['A','B','C','D']); // jawaban benar
            $table->timestamps();

            $table->unique(['pelatihan_id', 'nomor']); // nomor pertanyaan unik per pelatihan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_tests');
    }
};
