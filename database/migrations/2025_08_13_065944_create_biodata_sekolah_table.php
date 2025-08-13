<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('biodata_sekolah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('school_name');
            $table->string('school_address')->nullable();
            $table->string('competence')->nullable();
            $table->string('class')->nullable();
            $table->string('dinas_branch')->nullable();
            $table->timestamps();

            $table->unique('user_id'); // satu biodata sekolah per user
        });
    }
    public function down(): void {
        Schema::dropIfExists('biodata_sekolah');
    }
};
