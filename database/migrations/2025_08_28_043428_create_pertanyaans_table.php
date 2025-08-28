<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pertanyaans')) {
            Schema::create('pertanyaans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tes_id')->constrained('tes')->cascadeOnDelete();
                $table->unsignedInteger('nomor');
                $table->text('teks_pertanyaan');
                $table->string('gambar')->nullable();
                $table->enum('tipe_jawaban', ['pilihan_ganda', 'skala_likert'])->default('pilihan_ganda');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pertanyaans')) {
            Schema::dropIfExists('pertanyaans');
        }
    }
};
