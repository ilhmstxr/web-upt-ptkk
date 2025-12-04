<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asrama', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('gender', ['Laki-laki', 'Perempuan', 'Campur'])->default('Campur');
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asrama');
    }
};
