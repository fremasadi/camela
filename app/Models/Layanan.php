<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\ImageArrayCast;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanans';

    protected $fillable = [
        'kategori_id',
        'name',
        'deskripsi',
        'harga',
        'image',
        'estimasi_menit',
    ];

    protected $casts = [
        'image' => ImageArrayCast::class,
        'harga' => 'decimal:2',
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriLayanan::class, 'kategori_id', 'id');
    }

    // app/Models/Layanan.php

public function promo()
{
    return $this->hasMany(PromoLayanan::class, 'layanan_id', 'id');
}

// Tambahkan accessor untuk promo aktif
public function getPromoAktifAttribute()
{
    $promo = $this->promo()
        ->whereDate('tanggal_mulai', '<=', now())
        ->whereDate('tanggal_selesai', '>=', now())
        ->first();

    if ($promo) {
        return [
            'diskon_persen' => $promo->diskon_persen,
            'harga_diskon' => round($this->harga * (1 - $promo->diskon_persen / 100), 2),
            'tanggal_mulai' => $promo->tanggal_mulai->toDateString(),
            'tanggal_selesai' => $promo->tanggal_selesai->toDateString(),
        ];
    }

    return null;
}

}
