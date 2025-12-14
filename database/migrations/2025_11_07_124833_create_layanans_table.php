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
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')
                ->constrained('kategori_layanans', 'id')
                ->onDelete('cascade');
            $table->string('name');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->json('image')->nullable(); // menyimpan daftar URL/gambar dalam format JSON
            $table->integer('estimasi_menit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
