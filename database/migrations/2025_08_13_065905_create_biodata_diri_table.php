<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('biodata_diri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nik')->unique();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['Laki-laki','Perempuan']);
            $table->string('religion')->nullable();
            $table->string('address', 500)->nullable();
            $table->timestamps();

            $table->unique('user_id'); // satu biodata per user
        });
    }
    public function down(): void {
        Schema::dropIfExists('biodata_diri');
    }
};
