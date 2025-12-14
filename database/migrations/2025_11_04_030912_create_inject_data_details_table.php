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
        Schema::create('inject_data_details', function (Blueprint $table) {
            $table->id();
            // tes_id,kompetensi_id,foreign_id,Attribute,Value
            // $table->string('inject_data_id');
            $table->foreignId('unique_keys')->constrained('inject_data', 'unique_key')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('pelatihan_id');
            $table->string('tes_id');
            $table->string('kompetensi_id');
            $table->integer('foreign_id');
            // $table->longText('attribute');
            // $table->text('attribute');
            $table->string('attribute');
            // $table->string('value');
            $table->longText('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inject_data_details');
    }
};
