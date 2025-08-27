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
<<<<<<< HEAD:database/migrations/2025_08_26_213335_create_pre_test_answers_table.php
        Schema::create('pre_test_answers', function (Blueprint $table) {
=======
        Schema::create('tes', function (Blueprint $table) {
>>>>>>> a971bdb29d4a8431715c28855cee0face02b9a89:database/migrations/2025_08_27_004959_create_tes_table.php
            $table->id();
            $table->string('judul');
            $table->enum('tipe', ['pre-test', 'post-test']);
            $table->string('bidang');
            $table->string('pelatihan');
            $table->integer('durasi_menit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tes');
    }
};
