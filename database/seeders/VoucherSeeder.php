<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'BRONZE',
                'name' => 'Voucher Bronze',
                'required_points' => 25,
                'discount_type' => 'fixed',
                'discount_value' => 10000,
                'max_discount' => null,
                'min_transaction' => 75000,
                'expired_days' => 30,
                'status' => 'active',
            ],
            [
                'code' => 'SILVER',
                'name' => 'Voucher Silver',
                'required_points' => 50,
                'discount_type' => 'fixed',
                'discount_value' => 25000,
                'max_discount' => null,
                'min_transaction' => 150000,
                'expired_days' => 30,
                'status' => 'active',
            ],
            [
                'code' => 'GOLD',
                'name' => 'Voucher Gold',
                'required_points' => 100,
                'discount_type' => 'fixed',
                'discount_value' => 60000,
                'max_discount' => null,
                'min_transaction' => 300000,
                'expired_days' => 45,
                'status' => 'active',
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::updateOrCreate(
                ['code' => $voucher['code']],
                $voucher,
            );
        }

        $this->command->info('Voucher Bronze, Silver, dan Gold berhasil di-seed.');
    }
}
