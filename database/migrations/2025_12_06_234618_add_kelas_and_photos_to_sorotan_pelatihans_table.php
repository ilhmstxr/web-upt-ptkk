<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan kolom photos ada (kalau sudah ada, tidak ngapa-ngapain)
        if (!Schema::hasColumn('sorotan_pelatihans', 'photos')) {
            Schema::table('sorotan_pelatihans', function (Blueprint $table) {
                $table->json('photos')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        // Aman saat rollback
        if (Schema::hasColumn('sorotan_pelatihans', 'photos')) {
            Schema::table('sorotan_pelatihans', function (Blueprint $table) {
                $table->dropColumn('photos');
            });
        }
    }
};
