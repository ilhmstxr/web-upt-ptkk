<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Timestamps sudah pernah ditambahkan di migration sebelumnya.
        // Migration ini dikosongkan supaya tidak bentrok (duplicate column).
    }

    public function down(): void
    {
        // Tidak ada perubahan yang perlu di-rollback di sini.
    }
};
