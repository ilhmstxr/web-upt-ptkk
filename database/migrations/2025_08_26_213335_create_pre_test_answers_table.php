<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_test_answers');
    }
};
