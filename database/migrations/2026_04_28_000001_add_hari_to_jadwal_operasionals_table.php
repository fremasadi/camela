<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hariOptions = [
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu',
        ];

        Schema::table('jadwal_operasionals', function (Blueprint $table) use ($hariOptions) {
            $table->enum('hari', array_keys($hariOptions))
                ->nullable()
                ->after('id');
        });

        $jadwalList = DB::table('jadwal_operasionals')
            ->orderBy('id')
            ->get();

        if ($jadwalList->count() === 1) {
            $jadwal = $jadwalList->first();
            $hariKeys = array_keys($hariOptions);

            DB::table('jadwal_operasionals')
                ->where('id', $jadwal->id)
                ->update(['hari' => $hariKeys[0]]);

            foreach (array_slice($hariKeys, 1) as $hari) {
                DB::table('jadwal_operasionals')->insert([
                    'hari' => $hari,
                    'jam_buka' => $jadwal->jam_buka,
                    'jam_tutup' => $jadwal->jam_tutup,
                    'status' => $jadwal->status,
                    'keterangan' => $jadwal->keterangan,
                    'created_at' => $jadwal->created_at,
                    'updated_at' => $jadwal->updated_at,
                ]);
            }
        } elseif ($jadwalList->isNotEmpty()) {
            $availableHari = array_keys($hariOptions);

            foreach ($jadwalList as $jadwal) {
                if ($jadwal->hari || $availableHari === []) {
                    continue;
                }

                DB::table('jadwal_operasionals')
                    ->where('id', $jadwal->id)
                    ->update(['hari' => array_shift($availableHari)]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('jadwal_operasionals', function (Blueprint $table) {
            $table->dropColumn('hari');
        });
    }
};
