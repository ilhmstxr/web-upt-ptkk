<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opsi_jawabans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pertanyaan_id')
                ->constrained('pertanyaans')
                ->cascadeOnDelete();

            $table->text('teks_opsi')->nullable(); // bisa kosong kalau opsi hanya gambar
            $table->string('gambar')->nullable();
            $table->boolean('apakah_benar')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opsi_jawabans');
    }
};
