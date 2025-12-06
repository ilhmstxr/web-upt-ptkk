<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bidang', function (Blueprint $table) {
            // tambahkan kolom setelah 'kode' (boleh disesuaikan)
            $table->tinyInteger('kelas_keterampilan')
                ->default(1)     // default: Kelas Keterampilan & Teknik
                ->comment('1 = Kelas Keterampilan & Teknik, 0 = MJC')
                ->after('kode');
        });
    }

    public function down(): void
    {
        Schema::table('bidang', function (Blueprint $table) {
            $table->dropColumn('kelas_keterampilan');
        });
    }
};
