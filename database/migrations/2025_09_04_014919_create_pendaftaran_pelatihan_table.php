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
            
            // PENTING: Uncomment ini jika Controller Anda memakai 'bidang_id'
            // $table->foreignId('bidang_id')->constrained('bidang')->cascadeOnDelete(); 

            $table->foreignId('bidang_pelatihan_id')->nullable()
                ->constrained('bidang_pelatihan')
                ->cascadeOnDelete();
            
            $table->string('kelas')->nullable();

            // --- TAMBAHAN TOKEN DI SINI ---
            $table->string('assessment_token', 60)->nullable()->unique();
            $table->timestamp('token_expires_at')->nullable();
            $table->index('assessment_token');
            // ------------------------------

            $table->integer('nilai_pre_test')->default(0);
            $table->integer('nilai_post_test')->default(0);
            $table->integer('nilai_praktek')->default(0);
            $table->integer('rata_rata')->default(0);
            $table->integer('nilai_survey')->default(0);
            $table->enum('status', ['Lulus', 'Tidak Lulus', 'Belum Lulus'])->default('Belum Lulus');
            $table->enum('status_pendaftaran', ['Pending', 'Verifikasi', 'Diterima', 'Ditolak'])->default('Pending');
            $table->string('nomor_registrasi')->unique();
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
