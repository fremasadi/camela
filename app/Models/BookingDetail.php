<?php
// app/Models/BookingDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingDetail extends Model
{
    protected $fillable = [
        'booking_id',
        'layanan_id',
        'harga',
        'qty',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    // Accessor untuk subtotal
    public function getSubtotalAttribute()
    {
        return $this->harga * $this->qty;
    }
}