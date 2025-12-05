<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Token assessment harus ada di tabel pendaftaran_pelatihan
     * karena satu peserta dapat mendaftar ke banyak pelatihan, 
     * dan token harus unik untuk setiap pendaftaran.
     */
    public function up(): void
    {
        // Menambahkan kolom ke tabel pendaftaran_pelatihan
        Schema::table('pendaftaran_pelatihan', function (Blueprint $table) {
            // Assessment Token (untuk login assessment)
            $table->string('assessment_token', 60)->nullable()->unique()->after('status_pendaftaran');
            // Masa kedaluwarsa token
            $table->timestamp('token_expires_at')->nullable()->after('assessment_token');

            // Opsional: Menambahkan indeks untuk performa pencarian token
            $table->index('assessment_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_pelatihan', function (Blueprint $table) {
            $table->dropColumn(['assessment_token', 'token_expires_at']);
        });
    }
};