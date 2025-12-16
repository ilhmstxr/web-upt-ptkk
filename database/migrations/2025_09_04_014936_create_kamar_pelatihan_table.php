<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar_pelatihans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kamar_id')
                ->constrained('kamars')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('pelatihan_id')
                ->constrained('pelatihan')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // snapshot kapasitas untuk pelatihan ini (boleh beda dari master)
            $table->unsignedInteger('total_beds')->default(0);
            $table->unsignedInteger('available_beds')->default(0);

            // aktif/nonaktif khusus untuk pelatihan ini
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['kamar_id', 'pelatihan_id'], 'kamar_pelatihans_kamar_pelatihan_unique');

            $table->index(['pelatihan_id', 'is_active'], 'kamar_pelatihans_pelatihan_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar_pelatihans');
    }
};
