<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sorotan_pelatihans', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('sorotan_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sorotan_pelatihan_id')
                  ->constrained('sorotan_pelatihans')
                  ->cascadeOnDelete();
            $table->string('path');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sorotan_fotos');
        Schema::dropIfExists('sorotan_pelatihans');
    }
};
