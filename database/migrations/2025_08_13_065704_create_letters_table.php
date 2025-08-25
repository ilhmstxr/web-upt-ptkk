<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('registration_id')->nullable()->constrained()->nullOnDelete();
            $table->string('letter_type'); // contoh: 'Surat Tugas', 'Perjalanan Dinas'
            $table->longText('content')->nullable();
            $table->enum('status', ['draft','sent','approved','rejected'])->default('draft');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('letters');
    }
};
