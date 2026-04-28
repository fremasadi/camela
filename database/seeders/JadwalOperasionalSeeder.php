<?php

namespace Database\Seeders;

use App\Models\JadwalOperasional;
use Illuminate\Database\Seeder;

class JadwalOperasionalSeeder extends Seeder
{
    public function run(): void
    {
        foreach (JadwalOperasional::hariOptions() as $hari => $label) {
            JadwalOperasional::updateOrCreate(
                ['hari' => $hari],
                [
                    'jam_buka' => '09:00',
                    'jam_tutup' => '20:00',
                    'status' => 'buka',
                    'keterangan' => "Jam operasional {$label}",
                ],
            );
        }
    }
}
