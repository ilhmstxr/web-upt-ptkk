<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('jawaban_users')) {
            Schema::create('jawaban_users', function (Blueprint $table) {
                $table->id();
                $table->foreignId('opsi_jawaban_id')->nullable()->constrained('opsi_jawaban')->cascadeOnDelete();
                $table->foreignId('pertanyaan_id')->constrained('pertanyaans')->cascadeOnDelete();
                $table->foreignId('percobaan_id')->constrained('percobaans')->cascadeOnDelete();
                $table->unsignedTinyInteger('nilai_jawaban')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('jawaban_users')) {
            Schema::dropIfExists('jawaban_users');
        }
    }
};
