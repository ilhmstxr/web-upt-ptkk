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
        Schema::create('email_template', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->comment('Pengenal unik untuk template');
            $table->string('subject')->comment('Subjek email (bisa mengandung placeholder)');
            $table->longText('body')->comment('Isi email dalam format HTML (bisa mengandung placeholder)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_template');
    }
};
