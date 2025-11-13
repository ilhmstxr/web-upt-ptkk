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
        Schema::create('instansi', function (Blueprint $table) {
            $table->id();
            $table->string('asal_instansi');
            $table->text('alamat_instansi');
            $table->string('kota_id');
            $table->string('kota');
            $table->string('bidang_keahlian');
            $table->string('kelas');
            $table->foreignId('cabangDinas_id')
                ->constrained('cabang_dinas')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->nullable()->unique()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instansi');
    }
};
