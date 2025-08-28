<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_test_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_test_id')
                  ->constrained('post_tests')
                  ->onDelete('cascade'); // pastikan FK mengarah ke tabel post_tests terbaru
            $table->string('answer');      // jawaban user
            $table->boolean('is_correct'); // benar/salah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_test_answers');
    }
};
