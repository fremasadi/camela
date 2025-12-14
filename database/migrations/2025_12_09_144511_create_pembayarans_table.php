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
        Schema::create('pembayarans', function (Blueprint $table) {
              $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('order_id')->unique();
            
            // Transaction Details
            $table->string('transaction_id')->nullable();
            $table->decimal('gross_amount', 15, 2);
            $table->string('transaction_status')->default('pending'); // pending, success, failed, expired, cancelled, etc.
            $table->string('fraud_status')->nullable(); // accept, challenge, deny
            
            // Payment Method
            $table->string('payment_type')->nullable(); // BANK_TRANSFER, QRIS, GOPAY
            $table->string('payment_gateway')->default('midtrans');
            $table->string('payment_gateway_reference_id')->nullable();
            
            // Bank Transfer / VA Details
            $table->string('bank')->nullable(); // bri, bni, bca, mandiri, permata
            $table->string('va_number')->nullable();
            
            // QRIS & E-Wallet Details
            $table->text('qr_url')->nullable(); // URL untuk QR Code
            $table->text('deeplink_url')->nullable(); // URL deeplink untuk GoPay, dll
            
            // Payment URL & Responses
            $table->text('payment_url')->nullable(); // Snap token atau payment URL
            $table->json('payment_gateway_response')->nullable(); // Full response dari Midtrans
            $table->json('midtrans_response')->nullable(); // Backup compatibility
            
            // Payment Proof (jika manual)
            $table->string('payment_proof')->nullable();
            
            // Timestamps
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamp('expired_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('order_id');
            $table->index('booking_id');
            $table->index('transaction_status');
            $table->index('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
