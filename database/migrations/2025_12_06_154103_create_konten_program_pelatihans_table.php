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
        Schema::create('konten_program_pelatihans', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->unique(); // langsung isi: MTU, Reguler, Aksel
            $table->text('deskripsi')->nullable();
            $table->string('hero_image')->nullable(); // foto besar
            $table->json('galeri')->nullable();       // array foto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten_program_pelatihans');
    }
};
