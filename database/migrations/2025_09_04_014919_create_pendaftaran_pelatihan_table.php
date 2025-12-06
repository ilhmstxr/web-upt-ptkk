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
        Schema::create('pendaftaran_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
            $table->foreignId('pelatihan_id')->constrained('pelatihan')->cascadeOnDelete();
            // $table->foreignId('kompetensi_id')
            //     ->constrained('kompetensi')
            //     ->cascadeOnDelete();
            $table->foreignId('kompetensi_pelatihan_id')->nullable()
                ->constrained('kompetensi_pelatihan')
                ->cascadeOnDelete();
            $table->string('kelas')->nullable();

            // IMPROVE: menambahkan kompetensi id 
            // $table->foreignId('kompetensi_id')->constrained('kompetensi')->cascadeOnDelete();
            $table->Integer('nilai_pre_test')->default(0);
            $table->Integer('nilai_post_test')->default(0);
            $table->Integer('nilai_praktek')->default(0);
            $table->Integer('rata_rata')->default(0);
            $table->Integer('nilai_survey')->default(0);
            $table->Enum('status', ['Lulus', 'Tidak Lulus', 'Belum Lulus'])->default('Belum Lulus');
            $table->Enum('status_pendaftaran', ['Pending', 'Verifikasi', 'Diterima', 'Ditolak'])->default('Pending');
            $table->String('nomor_registrasi')->unique();
            $table->timestamp('tanggal_pendaftaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pelatihan');
    }
};
