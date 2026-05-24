<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'required_points',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_transaction',
        'expired_days',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_transaction' => 'decimal:2',
    ];

    public function userVouchers(): HasMany
    {
        return $this->hasMany(UserVoucher::class);
    }
}
