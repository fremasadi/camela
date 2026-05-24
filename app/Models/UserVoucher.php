<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVoucher extends Model
{
    protected $fillable = [
        'user_id',
        'voucher_id',
        'used_booking_id',
        'code',
        'status',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_transaction',
        'expired_at',
        'used_at',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_transaction' => 'decimal:2',
        'expired_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function usedBooking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'used_booking_id');
    }

    public function calculateDiscount(float $total): float
    {
        if ($this->min_transaction && $total < (float) $this->min_transaction) {
            return 0;
        }

        $discount = $this->discount_type === 'percent'
            ? $total * ((float) $this->discount_value / 100)
            : (float) $this->discount_value;

        if ($this->max_discount) {
            $discount = min($discount, (float) $this->max_discount);
        }

        return min($discount, max($total - 1000, 0));
    }
}
