<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('percobaan', function (Blueprint $table) {
            // PK
            $table->id();

            // Kolom relasi (tanpa FK DB)
            $table->unsignedBigInteger('peserta_id')->nullable();
            $table->unsignedBigInteger('pesertaSurvei_id')->nullable();
            $table->unsignedBigInteger('pelatihan_id')->nullable();
            $table->unsignedBigInteger('tes_id');

            // 🔑 Flag legacy: data dari dump lama = 1 (default),
            // data baru dari aplikasi = 0 (kita atur di Model)
            $table->boolean('is_legacy')->default(true);

            // Kolom utama
            $table->enum('tipe', ['survey','pre-test','post-test'])->nullable();
            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai')->nullable();
            $table->decimal('skor', 5, 2)->nullable();
            $table->boolean('lulus')->default(false);
            $table->text('pesan_kesan')->nullable();
            $table->timestamps();

            /**
             * ======================================================
             *  TRIK CERDAS: UNIQUE KHUSUS DATA BARU SAJA
             *  - Data legacy (is_legacy = 1) → uniq_* = NULL → boleh dobel
             *  - Data baru (is_legacy = 0)  → uniq_* diisi → kena UNIQUE
             * ======================================================
             */

            // Untuk peserta biasa
            $table->string('uniq_peserta')->storedAs(
                "IF(is_legacy = 1 OR peserta_id IS NULL OR tipe IS NULL, NULL, CONCAT(tes_id,'-',peserta_id,'-',tipe))"
            );

            // Untuk peserta survei
            $table->string('uniq_peserta_survei')->storedAs(
                "IF(is_legacy = 1 OR pesertaSurvei_id IS NULL OR tipe IS NULL, NULL, CONCAT(tes_id,'-',pesertaSurvei_id,'-',tipe))"
            );

            // UNIQUE hanya berlaku kalau kolom ini TERISI (bukan NULL)
            $table->unique('uniq_peserta', 'uniq_peserta_unique');
            $table->unique('uniq_peserta_survei', 'uniq_peserta_survei_unique');

            // Index bantu query
            $table->index('peserta_id');
            $table->index('pesertaSurvei_id');
            $table->index('pelatihan_id');
            $table->index('tes_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('percobaan');
    }
};
