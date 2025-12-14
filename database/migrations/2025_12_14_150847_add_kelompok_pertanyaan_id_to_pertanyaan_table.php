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
        Schema::table('pertanyaan', function (Blueprint $table) {
            $table->foreignId('kelompok_pertanyaan_id')
                ->nullable()
                ->constrained('kelompok_pertanyaans')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertanyaan', function (Blueprint $table) {
            $table->dropForeign(['kelompok_pertanyaan_id']);
            $table->dropColumn('kelompok_pertanyaan_id');
        });
    }
};
