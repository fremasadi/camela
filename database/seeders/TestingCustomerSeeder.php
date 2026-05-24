<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestingCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'testing@gmail.com'],
            [
                'name' => 'Customer Testing',
                'password' => Hash::make('password123'),
                'no_telp' => '081234567890',
                'role' => 'customer',
            ],
        );

        UserPoint::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => 'testing_bonus',
            ],
            [
                'booking_id' => null,
                'points' => 1000,
                'description' => 'Bonus point untuk testing redeem voucher',
            ],
        );

        $this->command->info('Akun testing berhasil dibuat: testing@camela.test / password123 dengan 1000 point.');
    }
}
