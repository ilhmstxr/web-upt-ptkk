<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// -----------------------------
// File: 2025_12_02_000002_create_beritas_table.php
// -----------------------------
return new class extends Migration
{
/**
* Run the migrations.
*/
public function up(): void
{
if (! Schema::hasTable('beritas')) {
Schema::create('beritas', function (Blueprint $table) {
$table->id();
$table->string('title');
$table->string('slug')->unique();
$table->text('content');
$table->string('image')->nullable();
$table->boolean('is_published')->default(false);
$table->timestamp('published_at')->nullable();
// timestamps agar Eloquent dapat mengisi created_at/updated_at
$table->timestamps();
});
}
}


/**
* Reverse the migrations.
*/
public function down(): void
{
Schema::dropIfExists('beritas');
}
};