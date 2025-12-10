<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCeritaKamisTable extends Migration
{
    public function up(): void
    {   
        Schema::create('cerita_kamis', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('slug')->nullable()->unique();
            $table->longText('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cerita_kamis');
    }
}
