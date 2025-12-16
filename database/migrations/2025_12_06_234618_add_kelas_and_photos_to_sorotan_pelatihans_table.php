<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sorotan_pelatihans', function (Blueprint $table) {
            // kolom untuk simpan array path foto
            $table->json('photos')
                ->nullable()
                ->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('sorotan_pelatihans', function (Blueprint $table) {
            $table->dropColumn('photos');
        });
    }
};
