<?php
// app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Pegawai;

class Booking extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'user_voucher_id',
        'pegawai_id',
        'tanggal_booking',
        'jam_booking',
        'jam_selesai',
        'status',
        'total_before_discount',
        'discount_amount',
        'total_harga',
        'jenis_pembayaran',
        'total_pembayaran',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'total_before_discount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'total_pembayaran' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function userVoucher(): BelongsTo
    {
        return $this->belongsTo(UserVoucher::class);
    }
}
