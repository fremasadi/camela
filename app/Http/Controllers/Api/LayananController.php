<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingDetail;
use App\Models\Layanan;

class LayananController extends Controller
{
    /**
     * Ambil semua layanan (opsional filter kategori_id)
     */
    public function index()
    {
        $query = Layanan::with('kategori')
            ->select('layanans.*')
            ->selectSub(
                BookingDetail::query()
                    ->selectRaw('COALESCE(SUM(qty), 0)')
                    ->whereColumn('booking_details.layanan_id', 'layanans.id'),
                'total_dipesan'
            )
            ->orderByDesc('total_dipesan')
            ->orderBy('name');

        // Optional: filter by kategori_id via query param
        if (request()->has('kategori_id')) {
            $query->where('kategori_id', request('kategori_id'));
        }

        $layanans = $query->get()->map(function ($layanan) {
            return [
                'id' => $layanan->id,
                'name' => $layanan->name,
                'deskripsi' => $layanan->deskripsi,
                'harga' => $layanan->harga,
                'estimasi_menit' => $layanan->estimasi_menit,
                'image' => $layanan->image,
                'total_dipesan' => (int) $layanan->total_dipesan,
                'kategori' => [
                    'id' => $layanan->kategori->id,
                    'name' => $layanan->kategori->name,
                ],
                'promo_aktif' => $layanan->promo_aktif, // data promo aktif atau null
            ];
        });

        return response()->json([
            'message' => 'Daftar layanan berhasil diambil',
            'data' => $layanans,
        ]);
    }


    /**
     * Ambil detail layanan berdasarkan ID
     */
    public function show($id)
    {
        $layanan = Layanan::with('kategori')->find($id);

        if (!$layanan) {
            return response()->json([
                'message' => 'Layanan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail layanan',
            'data' => [
                'id' => $layanan->id,
                'name' => $layanan->name,
                'deskripsi' => $layanan->deskripsi,
                'harga' => $layanan->harga,
                'estimasi_menit' => $layanan->estimasi_menit,
                'image' => $layanan->image,
                'kategori' => [
                    'id' => $layanan->kategori->id,
                    'name' => $layanan->kategori->name,
                ],
                'promo_aktif' => $layanan->promo_aktif,
            ],
        ]);
    }
}
