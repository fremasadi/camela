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
        Schema::create('booking_details', function (Blueprint $table) {
             $table->id();

    // Relasi ke bookings
    $table->foreignId('booking_id')
          ->constrained('bookings')
          ->onDelete('cascade');

    // Relasi ke layanan
    $table->foreignId('layanan_id')
          ->constrained('layanans')
          ->onDelete('cascade');

    // Harga di detail
    $table->decimal('harga', 10, 2);

    // Jumlah item
    $table->integer('qty');

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_details');
    }
};
