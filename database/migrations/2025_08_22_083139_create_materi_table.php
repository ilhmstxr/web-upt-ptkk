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
        // 1. Membuat tabel 'materi' (Harus dibuat lebih dulu karena dirujuk oleh materi_progress)
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique(); // Untuk URL yang lebih bersih
            $table->text('deskripsi')->nullable();
            $table->string('kategori', 100)->nullable(); // Kategori atau Topik Materi
            $table->integer('durasi')->nullable(); // Estimasi durasi dalam menit
            $table->longText('konten')->nullable(); // Konten materi dalam format Rich Text (HTML)
            $table->string('file_pendukung')->nullable(); // Jika ada file terpisah (PDF, video URL, dll)
            $table->timestamps();
        });

        // 2. Membuat tabel 'materi_progress'
        Schema::create('materi_progress', function (Blueprint $table) {
            $table->id();
            // foreignId('user_id')->constrained('users') mengasumsikan tabel 'users' sudah ada
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // foreignId('materi_id')->constrained('materi') merujuk ke tabel yang baru dibuat di atas
            $table->foreignId('materi_id')->constrained('materi')->cascadeOnDelete();
            
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            
            // Memastikan satu pengguna hanya bisa punya satu entri progress untuk satu materi
            $table->unique(['user_id', 'materi_id']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Harus menghapus 'materi_progress' terlebih dahulu karena memiliki foreign key ke 'materi'
        Schema::dropIfExists('materi_progress');
        
        // Kemudian hapus tabel 'materi'
        Schema::dropIfExists('materi');
    }
};