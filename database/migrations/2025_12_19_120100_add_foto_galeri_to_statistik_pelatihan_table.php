<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('statistik_pelatihan', function (Blueprint $table) {
            $table->json('foto_galeri')->nullable()->after('rata_avg');
        });
    }

    public function down(): void
    {
        Schema::table('statistik_pelatihan', function (Blueprint $table) {
            $table->dropColumn('foto_galeri');
        });
    }
};
