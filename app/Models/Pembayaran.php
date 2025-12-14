<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'booking_id',
        'order_id',
        'transaction_id',
        'gross_amount',
        'transaction_status',
        'fraud_status',
        'payment_type',
        'payment_gateway',
        'payment_gateway_reference_id',
        'bank',
        'va_number',
        'qr_url',
        'deeplink_url',
        'payment_url',
        'payment_gateway_response',
        'midtrans_response',
        'payment_proof',
        'payment_date',
        'transaction_time',
        'settlement_time',
        'expired_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'payment_gateway_response' => 'array',
        'midtrans_response' => 'array',
        'payment_date' => 'datetime',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'payment_gateway_response',
        'midtrans_response',
    ];

    protected $appends = [
        'is_paid',
        'is_expired',
        'status_label',
    ];

    /**
     * Relationship: Pembayaran belongs to Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Accessor: Check if payment is successful
     */
    public function getIsPaidAttribute()
    {
        return in_array($this->transaction_status, ['success', 'settlement', 'capture']);
    }

    /**
     * Accessor: Check if payment is expired
     */
    public function getIsExpiredAttribute()
    {
        if (!$this->expired_at) {
            return false;
        }
        
        return now()->greaterThan($this->expired_at) && !$this->is_paid;
    }

    /**
     * Accessor: Get human-readable status label
     */
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'success' => 'Berhasil',
            'settlement' => 'Berhasil',
            'capture' => 'Berhasil',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
            'partial_refunded' => 'Dikembalikan Sebagian',
            'challenge' => 'Tertunda (Challenge)',
            'deny' => 'Ditolak',
        ];

        return $statusLabels[$this->transaction_status] ?? 'Unknown';
    }

    /**
     * Accessor: Get payment method label
     */
    public function getPaymentMethodLabelAttribute()
    {
        $paymentLabels = [
            'BANK_TRANSFER' => 'Transfer Bank',
            'QRIS' => 'QRIS',
            'GOPAY' => 'GoPay',
        ];

        return $paymentLabels[$this->payment_type] ?? $this->payment_type;
    }

    /**
     * Accessor: Get bank name
     */
    public function getBankNameAttribute()
    {
        if (!$this->bank) {
            return null;
        }

        $bankNames = [
            'bri' => 'Bank BRI',
            'bni' => 'Bank BNI',
            'bca' => 'Bank BCA',
            'mandiri' => 'Bank Mandiri',
            'permata' => 'Bank Permata',
        ];

        return $bankNames[strtolower($this->bank)] ?? strtoupper($this->bank);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('transaction_status', $status);
    }

    /**
     * Scope: Filter by payment type
     */
    public function scopePaymentType($query, $type)
    {
        return $query->where('payment_type', $type);
    }

    /**
     * Scope: Only paid payments
     */
    public function scopePaid($query)
    {
        return $query->whereIn('transaction_status', ['success', 'settlement', 'capture']);
    }

    /**
     * Scope: Only pending payments
     */
    public function scopePending($query)
    {
        return $query->where('transaction_status', 'pending');
    }

    /**
     * Scope: Only expired payments
     */
    public function scopeExpired($query)
    {
        return $query->where('transaction_status', 'expired')
                     ->orWhere(function($q) {
                         $q->where('expired_at', '<', now())
                           ->whereNotIn('transaction_status', ['success', 'settlement', 'capture']);
                     });
    }

    /**
     * Scope: Filter by booking
     */
    public function scopeByBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    /**
     * Scope: Filter by order ID
     */
    public function scopeByOrderId($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    /**
     * Method: Check if can be retried
     */
    public function canRetry()
    {
        return in_array($this->transaction_status, ['failed', 'expired', 'cancelled']);
    }

    /**
     * Method: Check if can be cancelled
     */
    public function canCancel()
    {
        return $this->transaction_status === 'pending' && !$this->is_expired;
    }

    /**
     * Method: Get remaining time before expiry
     */
    public function getRemainingTime()
    {
        if (!$this->expired_at || $this->is_paid) {
            return null;
        }

        $now = now();
        if ($now->greaterThan($this->expired_at)) {
            return 0;
        }

        return $now->diffInSeconds($this->expired_at);
    }

    /**
     * Method: Get payment instructions based on type
     */
    public function getPaymentInstructions()
    {
        $instructions = [];

        switch ($this->payment_type) {
            case 'BANK_TRANSFER':
                $instructions = [
                    'type' => 'Virtual Account',
                    'bank' => $this->bank_name,
                    'va_number' => $this->va_number,
                    'amount' => $this->gross_amount,
                    'steps' => [
                        'Buka aplikasi mobile banking atau ATM',
                        'Pilih menu Transfer / Pembayaran',
                        'Pilih Virtual Account / ' . $this->bank_name,
                        'Masukkan nomor VA: ' . $this->va_number,
                        'Masukkan nominal: Rp ' . number_format($this->gross_amount, 0, ',', '.'),
                        'Konfirmasi dan selesaikan pembayaran',
                    ]
                ];
                break;

            case 'QRIS':
                $instructions = [
                    'type' => 'QRIS',
                    'qr_url' => $this->qr_url,
                    'amount' => $this->gross_amount,
                    'steps' => [
                        'Buka aplikasi e-wallet atau mobile banking',
                        'Pilih menu Scan QR / QRIS',
                        'Scan QR Code yang tersedia',
                        'Konfirmasi nominal pembayaran',
                        'Selesaikan pembayaran',
                    ]
                ];
                break;

            case 'GOPAY':
                $instructions = [
                    'type' => 'GoPay',
                    'qr_url' => $this->qr_url,
                    'deeplink_url' => $this->deeplink_url,
                    'amount' => $this->gross_amount,
                    'steps' => [
                        'Klik tombol "Bayar dengan GoPay"',
                        'Aplikasi GoPay akan terbuka otomatis',
                        'Atau scan QR Code dengan aplikasi Gojek',
                        'Konfirmasi pembayaran di aplikasi',
                        'Masukkan PIN GoPay Anda',
                    ]
                ];
                break;

            default:
                $instructions = [
                    'type' => 'Unknown',
                    'message' => 'Silakan hubungi customer service untuk informasi pembayaran'
                ];
        }

        return $instructions;
    }
}