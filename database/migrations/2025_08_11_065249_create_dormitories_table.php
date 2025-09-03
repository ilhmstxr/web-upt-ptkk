<?php

// database/migrations/2025_08_13_000004_create_dormitories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dormitories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['Laki-laki','Perempuan']);
            $table->integer('total_rooms')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('dormitories');
    }
};
