<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom hanya kalau BELUM ada
        if (!Schema::hasColumn('kompetensi', 'gambar')) {
            Schema::table('kompetensi', function (Blueprint $table) {
                $table->string('gambar')
                    ->nullable()
                    ->after('kelas_keterampilan');
            });
        }
    }

    public function down(): void
    {
        Schema::table('kompetensi', function (Blueprint $table) {
            // Hapus kolom hanya kalau ADA
            if (Schema::hasColumn('kompetensi', 'gambar')) {
                $table->dropColumn('gambar');
            }
        });
    }
};
