<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statistik_pelatihan', function (Blueprint $table) {
            $table->id();

            $table->string('batch', 150);

            $table->foreignId('pelatihan_id')
                ->constrained('pelatihan')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // rata-rata nilai (kelas / pelatihan)
            $table->decimal('pre_avg', 6, 2)->default(0);
            $table->decimal('post_avg', 6, 2)->default(0);
            $table->decimal('praktek_avg', 6, 2)->default(0);
            $table->decimal('rata_avg', 6, 2)->default(0);

            $table->timestamps();

            $table->index('batch');
            $table->index('pelatihan_id');

            // biar tidak dobel ringkasan untuk batch+pelatihan yang sama
            $table->unique(['batch', 'pelatihan_id'], 'stat_pelatihan_batch_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistik_pelatihan');
    }
};
