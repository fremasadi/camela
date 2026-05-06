<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    /**
     * Status booking yang masih dianggap memakai slot pegawai.
     */
    private const BOOKING_STATUSES_MENGGUNAKAN_SLOT = [
        'pending',
        'confirmed',
    ];

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
        return self::queryTersedia($tanggal, $jamMulai, $jamSelesai)->first();
    }

    /**
     * Hitung jumlah pegawai aktif yang masih tersedia pada slot tertentu.
     */
    public static function jumlahTersedia(string $tanggal, string $jamMulai, string $jamSelesai): int
    {
        return self::queryTersedia($tanggal, $jamMulai, $jamSelesai)->count();
    }

    /**
     * Query pegawai aktif yang tidak sedang memakai slot pada rentang waktu tertentu.
     */
    private static function queryTersedia(string $tanggal, string $jamMulai, string $jamSelesai)
    {
        return self::where('status', 'aktif')
            ->whereDoesntHave('bookings', function ($q) use ($tanggal, $jamMulai, $jamSelesai) {
                $q->whereDate('tanggal_booking', $tanggal)
                    ->whereIn('status', self::BOOKING_STATUSES_MENGGUNAKAN_SLOT)
                    ->where('jam_booking', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            });
    }
}
