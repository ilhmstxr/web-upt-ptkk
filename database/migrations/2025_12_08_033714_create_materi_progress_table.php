<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materi_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_pelatihan_id')
                ->constrained('pendaftaran_pelatihan')
                ->cascadeOnDelete();
            $table->foreignId('materi_id')
                ->constrained('materi_pelatihan')
                ->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['pendaftaran_pelatihan_id', 'materi_id'], 'mp_pendaftaran_materi_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_progress');
    }
};
