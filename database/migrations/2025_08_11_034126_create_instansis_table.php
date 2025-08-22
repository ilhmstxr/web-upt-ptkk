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
        Schema::create('instansis', function (Blueprint $table) {
            $table->id();
            // --- Data Instansi ---
            $table->string('asal_instansi');
            $table->text('alamat_instansi');
            $table->string('bidang_keahlian');
            $table->string('kelas');
            // $table->string('cabang_dinas_wilayah');
            $table->foreignId('cabang_dinas_id')
                ->constrained('cabang_dinas')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Relasi ke tabel cabang_dinas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instansis');
    }
};
