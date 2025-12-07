<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penempatan_asrama', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peserta_id')
                ->constrained('peserta')
                ->cascadeOnDelete();

            $table->foreignId('pelatihan_id')
                ->constrained('pelatihan')
                ->cascadeOnDelete();

            $table->foreignId('asrama_id')
                ->constrained('asrama')
                ->cascadeOnDelete();

            $table->foreignId('kamar_id')
                ->constrained('kamar')
                ->cascadeOnDelete();

            $table->string('periode')->nullable();

            $table->timestamps();

            // biar peserta tidak dobel ditempatkan pada pelatihan yg sama
            $table->unique(['peserta_id', 'pelatihan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penempatan_asrama');
    }
};
