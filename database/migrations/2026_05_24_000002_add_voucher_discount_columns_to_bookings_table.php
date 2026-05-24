<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('user_voucher_id')
                ->nullable()
                ->after('user_id')
                ->constrained('user_vouchers')
                ->onDelete('set null');

            $table->decimal('total_before_discount', 10, 2)->default(0)->after('status');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('total_before_discount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['user_voucher_id']);
            $table->dropColumn(['user_voucher_id', 'total_before_discount', 'discount_amount']);
        });
    }
};
