<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Relasi ke Layanan
    public function layanan()
    {
        return $this->hasMany(Layanan::class, 'kategori_id', 'id');
    }
}
