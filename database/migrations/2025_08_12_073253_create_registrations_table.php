<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Step 1: Personal Data
            $table->string('name');
            $table->string('nik')->unique();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('gender');
            $table->string('religion')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Step 2: School Data
            $table->string('school_name')->nullable();
            $table->string('school_address')->nullable();
            $table->string('competence')->nullable();
            $table->string('class')->nullable();
            $table->string('dinas_branch')->nullable();

            // Step 3: Document references (kalau mau simpan langsung di sini)
            $table->string('ktp_path')->nullable();
            $table->string('ijazah_path')->nullable();
            $table->string('surat_tugas_path')->nullable();
            $table->string('surat_tugas_nomor')->nullable();
            $table->string('surat_sehat_path')->nullable();
            $table->string('pas_foto_path')->nullable();

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('registrations');
    }
};
