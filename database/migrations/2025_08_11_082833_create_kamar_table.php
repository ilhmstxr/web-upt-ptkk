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
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asrama_id')->constrained('asrama')->cascadeOnDelete();
            $table->string('nomor_kamar');
            $table->integer('lantai');
            $table->integer('total_beds')->default(4);
            $table->integer('available_beds')->default(4);
            $table->enum('status', ['Tersedia', 'Penuh', 'Rusak', 'Perbaikan'])->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
