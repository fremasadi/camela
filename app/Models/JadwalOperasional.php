<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalOperasional extends Model
{
    protected $fillable = [
        'jam_buka',
        'jam_tutup',
        'status',
        'keterangan',
    ];
}
