<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingExportPdfController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->input('tableFilters', []);
        $tanggalFilter = $filters['tanggal_booking'] ?? [];

        $query = Booking::query()
            ->with(['user', 'pegawai'])
            ->when(
                $tanggalFilter['dari'] ?? null,
                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_booking', '>=', $date),
            )
            ->when(
                $tanggalFilter['sampai'] ?? null,
                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_booking', '<=', $date),
            )
            ->orderByDesc('created_at');

        $bookings = $query->get();

        return view('exports.bookings-pdf', [
            'bookings' => $bookings,
            'tanggalDari' => $tanggalFilter['dari'] ?? null,
            'tanggalSampai' => $tanggalFilter['sampai'] ?? null,
            'totalPendapatan' => $bookings->sum('total_harga'),
        ]);
    }
}
