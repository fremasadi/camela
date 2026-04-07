<?php

namespace Database\Seeders;

use App\Models\JadwalOperasional;
use Illuminate\Database\Seeder;

class JadwalOperasionalSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya 1 record — admin bisa ubah jam buka/tutup lewat panel
        JadwalOperasional::create([
            'jam_buka'   => '09:00',
            'jam_tutup'  => '20:00',
            'status'     => 'buka',
            'keterangan' => 'Jam operasional normal',
        ]);
    }
}
