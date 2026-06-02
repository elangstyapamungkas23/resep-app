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
       Schema::create('reseps', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
          ->constrained('users')
          ->onDelete('cascade');

    $table->foreignId('kategori_id')
          ->constrained('kategoris')
          ->onDelete('cascade');

    $table->string('nama_resep');
    $table->text('deskripsi');
    $table->text('bahan');
    $table->text('langkah');

    $table->string('gambar')->nullable();

    $table->timestamps();
});

Schema::table('reseps', function (Blueprint $table) {

    $table->foreignId('user_id')
        ->nullable()
        ->after('id')
        ->constrained()
        ->onDelete('cascade');

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
