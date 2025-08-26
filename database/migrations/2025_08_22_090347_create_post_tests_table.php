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
        Schema::create('post_tests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pelatihan_id')->constrained('pelatihans')->onDelete('cascade');
    $table->integer('nomor');
    $table->text('question');
    $table->string('option_a');
    $table->string('option_b');
    $table->string('option_c');
    $table->string('option_d');
    $table->enum('correct_answer', ['A','B','C','D']);
    $table->timestamps();

    $table->unique(['pelatihan_id', 'nomor']); // nomor pertanyaan unik per pelatihan
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tests');
    }
};
