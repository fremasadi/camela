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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Foreign key ke users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Tanggal & jam booking
            $table->date('tanggal_booking');
            $table->time('jam_booking');

            // Status booking
            $table->string('status');

            // Total harga (decimal, misal 10 digit, 2 angka di belakang koma)
            $table->decimal('total_harga', 10, 2);

            // Jenis pembayaran: dp / lunas
            $table->enum('jenis_pembayaran', ['dp', 'lunas']);

            // Total pembayaran (jika mau numeric, pakai decimal)
            $table->decimal('total_pembayaran', 10, 2);

            $table->timestamps(); // created_at & updated_at
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
