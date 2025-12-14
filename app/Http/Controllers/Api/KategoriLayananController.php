<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriLayanan;

class KategoriLayananController extends Controller
{
    /**
     * Ambil semua kategori saja tanpa layanan
     */
    public function index()
{
    $kategori = KategoriLayanan::orderByRaw("
        CASE WHEN name = 'Bundling' THEN 0 ELSE 1 END, id ASC
    ")->get();

    return response()->json([
        'message' => 'Daftar kategori berhasil diambil',
        'data' => $kategori,
    ]);
}


   
}
