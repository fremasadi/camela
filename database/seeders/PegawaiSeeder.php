<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = [
            ['name' => 'Sari',    'no_telp' => '081234567801', 'status' => 'aktif'],
            ['name' => 'Dewi',    'no_telp' => '081234567802', 'status' => 'aktif'],
            ['name' => 'Rina',    'no_telp' => '081234567803', 'status' => 'aktif'],
            ['name' => 'Fitri',   'no_telp' => '081234567804', 'status' => 'aktif'],
            ['name' => 'Mega',    'no_telp' => '081234567805', 'status' => 'nonaktif'],
        ];

        foreach ($pegawais as $data) {
            Pegawai::create($data);
        }
    }
}
