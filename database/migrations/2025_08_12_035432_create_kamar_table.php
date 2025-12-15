<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();

            $table->foreignId('asrama_id')
                ->constrained('asramas')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('nomor_kamar');

            // kapasitas master kamar (default dari config)
            $table->unsignedInteger('total_beds')->default(0);

            // kamar master bisa di-nonaktifkan (misal rusak permanen)
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // nomor kamar tidak boleh dobel dalam 1 asrama
            $table->unique(['asrama_id', 'nomor_kamar'], 'kamars_asrama_nomor_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
