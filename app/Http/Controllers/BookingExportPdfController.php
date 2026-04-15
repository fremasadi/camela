<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingExportPdfController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->input('tableFilters', []);
        $tanggalFilter = $filters['tanggal_booking'] ?? [];
        $search = trim((string) $request->input('tableSearch', ''));
        $sortColumn = $request->input('tableSortColumn', 'created_at');
        $sortDirection = $request->input('tableSortDirection', 'desc');

        $allowedSortColumns = [
            'order_id',
            'tanggal_booking',
            'jam_booking',
            'status',
            'total_harga',
            'total_pembayaran',
            'created_at',
        ];

        if (! in_array($sortColumn, $allowedSortColumns, true)) {
            $sortColumn = 'created_at';
        }

        if (! in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }

        $query = Booking::query()
            ->with(['user', 'pegawai'])
            ->when(
                filled($search),
                function (Builder $query) use ($search): Builder {
                    return $query->where(function (Builder $query) use ($search): Builder {
                        return $query
                            ->where('order_id', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhereHas('user', fn (EloquentBuilder $userQuery): EloquentBuilder => $userQuery->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('pegawai', fn (EloquentBuilder $pegawaiQuery): EloquentBuilder => $pegawaiQuery->where('name', 'like', "%{$search}%"));
                    });
                },
            )
            ->when(
                $tanggalFilter['dari'] ?? null,
                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_booking', '>=', $date),
            )
            ->when(
                $tanggalFilter['sampai'] ?? null,
                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_booking', '<=', $date),
            )
            ->orderBy($sortColumn, $sortDirection);

        $bookings = $query->get();

        return view('exports.bookings-pdf', [
            'bookings' => $bookings,
            'tanggalDari' => $tanggalFilter['dari'] ?? null,
            'tanggalSampai' => $tanggalFilter['sampai'] ?? null,
            'search' => $search,
            'totalPendapatan' => $bookings->sum('total_harga'),
        ]);
    }
}
