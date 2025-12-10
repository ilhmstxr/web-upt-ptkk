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

            // FK ke pelatihan (parent)
            $table->foreignId('pelatihan_id')
                ->constrained('pelatihan')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('name');

            $table->enum('gender', ['Laki-laki', 'Perempuan', 'Campur'])
                ->default('Campur');

            $table->integer('total_kamar')->default(0);
            $table->text('alamat')->nullable();

            $table->timestamps();

            $table->unique(['pelatihan_id', 'name', 'gender']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asrama');
    }
};
