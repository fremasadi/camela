<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class JadwalOperasional extends Model
{
    public const HARI_OPTIONS = [
        'senin' => 'Senin',
        'selasa' => 'Selasa',
        'rabu' => 'Rabu',
        'kamis' => 'Kamis',
        'jumat' => 'Jumat',
        'sabtu' => 'Sabtu',
        'minggu' => 'Minggu',
    ];

    protected $fillable = [
        'hari',
        'jam_buka',
        'jam_tutup',
        'status',
        'keterangan',
    ];

    public static function hariOptions(): array
    {
        return self::HARI_OPTIONS;
    }

    public static function hariDariTanggal(string $tanggal, ?string $timezone = null): string
    {
        $timezone ??= config('app.timezone', 'UTC');
        $dayIndex = Carbon::parse($tanggal, $timezone)->dayOfWeek;
        $hariKeys = array_keys(self::HARI_OPTIONS);

        return $hariKeys[$dayIndex === 0 ? 6 : $dayIndex - 1];
    }
}
