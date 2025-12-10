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

            // FK ke pelatihan (parent)
            $table->foreignId('pelatihan_id')
                ->constrained('pelatihan')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // FK ke asrama (parent)
            $table->foreignId('asrama_id')
                ->constrained('asrama')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('nomor_kamar');

            // Atas / Bawah saja
            $table->enum('lantai', ['atas', 'bawah'])
                ->default('bawah');

            $table->integer('total_beds')->default(0);
            $table->integer('available_beds')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['asrama_id', 'nomor_kamar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
