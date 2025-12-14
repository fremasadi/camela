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
        Schema::create('jadwal_operasionals', function (Blueprint $table) {
            $table->id();
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->enum('status', ['buka', 'tutup'])->default('buka');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_operasionals');
    }
};
