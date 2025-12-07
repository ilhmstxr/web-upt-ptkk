<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_pelatihan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peserta_id')
                ->constrained('peserta')
                ->cascadeOnDelete();

            $table->foreignId('pelatihan_id')
                ->constrained('pelatihan')
                ->cascadeOnDelete();

            $table->foreignId('kompetensi_pelatihan_id')
                ->nullable()
                ->constrained('kompetensi_pelatihan')
                ->nullOnDelete();

            // ✅ dipakai di controller
            $table->foreignId('kompetensi_id')
                ->nullable()
                ->constrained('kompetensi')
                ->nullOnDelete();

            $table->string('kelas')->nullable();

            $table->integer('nilai_pre_test')->default(0);
            $table->integer('nilai_post_test')->default(0);
            $table->integer('nilai_praktek')->default(0);
            $table->integer('rata_rata')->default(0);
            $table->integer('nilai_survey')->default(0);

            $table->enum('status', ['Lulus', 'Tidak Lulus', 'Belum Lulus'])
                ->default('Belum Lulus');

            // ✅ pakai huruf kecil biar match controller & Filament
            $table->enum('status_pendaftaran', ['pending', 'diterima', 'ditolak'])
                ->default('pending');

            // nomor registrasi hasil generateToken()
            $table->string('nomor_registrasi')->unique();

            $table->timestamp('tanggal_pendaftaran')->nullable();

            // ✅ token assessment (diisi nanti = nomor_registrasi saat diterima admin)
            $table->string('assessment_token', 64)->nullable()->unique();
            $table->timestamp('assessment_token_sent_at')->nullable();

            $table->timestamps();

            $table->unique(['pelatihan_id', 'peserta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pelatihan');
    }
};
