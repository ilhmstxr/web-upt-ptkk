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
                  ->onDelete('cascade');
            $table->foreignId('bidang_id')
                  ->nullable()
                  ->constrained('bidangs')
                  ->onDelete('set null'); // relasi ke tabel Bidang
            $table->integer('nomor'); // nomor pertanyaan
            $table->string('pertanyaan');
            $table->string('opsi_a');
            $table->string('opsi_b');
            $table->string('opsi_c');
            $table->string('opsi_d');
            $table->enum('jawaban_benar', ['A','B','C','D']);
            $table->timestamps();

            $table->unique(['pelatihan_id', 'nomor']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_tests');
    }
};
