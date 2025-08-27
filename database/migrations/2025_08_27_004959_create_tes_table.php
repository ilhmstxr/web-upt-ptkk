<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
<<<<<<< HEAD:database/migrations/2025_08_26_213335_create_pre_test_answers_table.php
        Schema::create('pre_test_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');   // hapus jawaban jika user dihapus
            $table->foreignId('pre_test_id')
                  ->constrained('pre_tests')
                  ->onDelete('cascade');   // hapus jawaban jika pre_test dihapus
            $table->string('answer');      // jawaban user
            $table->boolean('is_correct'); // true = benar, false = salah
=======
        Schema::create('tes', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('tipe', ['pre-test', 'post-test']);
            $table->string('bidang');
            $table->string('pelatihan');
            $table->integer('durasi_menit');
>>>>>>> ilham:database/migrations/2025_08_27_004959_create_tes_table.php
            $table->timestamps();
        });
    }

    public function down(): void
    {
<<<<<<< HEAD:database/migrations/2025_08_26_213335_create_pre_test_answers_table.php
        Schema::dropIfExists('pre_test_answers');
=======
        Schema::dropIfExists('tes');
>>>>>>> ilham:database/migrations/2025_08_27_004959_create_tes_table.php
    }
};
