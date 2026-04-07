<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    protected $fillable = [
        'name',
        'no_telp',
        'status',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Cari pegawai aktif yang tidak punya booking bentrok di rentang waktu tertentu.
     */
    public static function tersedia(string $tanggal, string $jamMulai, string $jamSelesai): ?self
    {
        return self::where('status', 'aktif')
            ->whereDoesntHave('bookings', function ($q) use ($tanggal, $jamMulai, $jamSelesai) {
                $q->where('tanggal_booking', $tanggal)
                  ->whereNotIn('status', ['cancelled', 'rejected'])
                  ->where('jam_booking', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();
    }
}
