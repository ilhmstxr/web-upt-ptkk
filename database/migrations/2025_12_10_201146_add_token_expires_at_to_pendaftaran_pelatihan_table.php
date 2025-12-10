<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_pelatihan', function (Blueprint $table) {
            if (! Schema::hasColumn('pendaftaran_pelatihan', 'token_expires_at')) {
                $table->timestamp('token_expires_at')
                      ->nullable()
                      ->after('assessment_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_pelatihan', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftaran_pelatihan', 'token_expires_at')) {
                $table->dropColumn('token_expires_at');
            }
        });
    }
};
