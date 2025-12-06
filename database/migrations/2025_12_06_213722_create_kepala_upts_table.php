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
    // Kalau tabel sudah ada, jangan buat lagi
    if (Schema::hasTable('kepala_upts')) {
        return;
    }

    Schema::create('kepala_upts', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('jabatan')->nullable();
        $table->string('foto')->nullable();
        $table->string('nip')->nullable();
        $table->string('periode')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepala_upts');
    }
};
