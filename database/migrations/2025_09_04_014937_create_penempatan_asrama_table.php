<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penempatan_asramas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peserta_id')
                ->constrained('peserta')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('pelatihan_id')
                ->constrained('pelatihan')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // kamar yang dipakai dalam konteks pelatihan
            $table->foreignId('kamar_pelatihan_id')
                ->constrained('kamar_pelatihans')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            /**
             * Gender optional.
             * Karena kamu bilang pembagian tidak fixed dari asrama.
             * Kalau mau simpan snapshot gender peserta saat penempatan, pakai nullable.
             */
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();

            $table->timestamps();

            // satu peserta hanya boleh 1 penempatan pada 1 pelatihan
            $table->unique(['peserta_id', 'pelatihan_id'], 'penempatan_asramas_peserta_pelatihan_unique');

            $table->index(['pelatihan_id'], 'penempatan_asramas_pelatihan_idx');
            $table->index(['kamar_pelatihan_id'], 'penempatan_asramas_kamar_pelatihan_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penempatan_asramas');
    }
};
