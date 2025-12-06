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
        Schema::create('kompetensi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kompetensi');
            $table->text('deskripsi')->nullable();
            $table->text('kode')->nullable();
            $table->string('kelas_keterampilan')->nullable();
            $table->string('gambar')->nullable(); // Image path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompetensi');
    }
};