<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoLayanan extends Model
{
    use HasFactory;

    protected $table = 'promo_layanans';

    protected $fillable = [
        'layanan_id',
        'diskon_persen',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Relasi ke Layanan
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    /**
     * Cek apakah promo sedang aktif
     */
    public function getIsAktifAttribute(): bool
    {
        $today = now()->toDateString();

        return $this->tanggal_mulai->toDateString() <= $today &&
               $this->tanggal_selesai->toDateString() >= $today;
    }

    /**
     * Ambil label status aktif/tidak aktif
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_aktif ? 'Aktif' : 'Nonaktif';
    }
}
