<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bidang', function (Blueprint $table) {
            $table->string('gambar')
                ->nullable()
                ->after('kelas_keterampilan'); // atau after('kode') kalau mau
        });
    }

    public function down(): void
    {
        Schema::table('bidang', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }
};
