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
        Schema::create('kompetensi_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->constrained('pelatihan')->onDelete('cascade');
            $table->foreignId('kompetensi_id')->constrained('kompetensi')->onDelete('cascade');

            $table->string('lokasi')->nullable();
            $table->string('kota')->nullable();

            $table->string('kode_kompetensi_pelatihan')->nullable();

            // Kolom Tambahan untuk Sesi/Jadwal
            $table->foreignId('instruktur_id')->nullable()->constrained('instruktur')->nullOnDelete();
            // Opsional, tapi sangat direkomendasikan
            $table->timestamps(); // Membuat kolom created_at dan updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompetensi_pelatihan');
    }
};
