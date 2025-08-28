<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('opsi_jawaban')) {
            Schema::create('opsi_jawaban', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pertanyaan_id')->constrained('pertanyaans')->cascadeOnDelete();
                $table->text('teks_opsi')->nullable();
                $table->string('gambar')->nullable();
                $table->boolean('apakah_benar')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('opsi_jawaban')) {
            Schema::dropIfExists('opsi_jawaban');
        }
    }
};
