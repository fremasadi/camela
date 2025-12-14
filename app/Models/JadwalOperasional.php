<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalOperasional extends Model
{
    use HasFactory;

    protected $table = 'jadwal_operasionals';

    protected $fillable = [
        'jam_buka',
        'jam_tutup',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'jam_buka' => 'datetime:H:i',
        'jam_tutup' => 'datetime:H:i',
    ];
}
