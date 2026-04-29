<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BookingExportService
{
    /**
     * @return Collection<int, Booking>
     */
    public function getFilteredBookings(Request $request): Collection
    {
        return $this->getFilteredQuery($request)->get();
    }

    public function getFilteredQuery(Request $request): Builder
    {
        $context = $this->getExportContext($request);

        return Booking::query()
            ->with(['user', 'pegawai'])
            ->where('status', 'confirmed')
            ->when(
                filled($context['search']),
                function (Builder $query) use ($context): Builder {
                    $search = $context['search'];

                    return $query->where(function (Builder $query) use ($search): Builder {
                        return $query
                            ->where('order_id', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhereHas('user', fn (Builder $userQuery): Builder => $userQuery->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('pegawai', fn (Builder $pegawaiQuery): Builder => $pegawaiQuery->where('name', 'like', "%{$search}%"));
                    });
                },
            )
            ->when(
                $context['tanggalDari'],
                fn (Builder $query, string $date): Builder => $query->whereDate('tanggal_booking', '>=', $date),
            )
            ->when(
                $context['tanggalSampai'],
                fn (Builder $query, string $date): Builder => $query->whereDate('tanggal_booking', '<=', $date),
            )
            ->orderBy($context['sortColumn'], $context['sortDirection']);
    }

    /**
     * @return array{
     *     search: string,
     *     sortColumn: string,
     *     sortDirection: string,
     *     tanggalDari: ?string,
     *     tanggalSampai: ?string
     * }
     */
    public function getExportContext(Request $request): array
    {
        $filters = $request->input('tableFilters', []);
        $tanggalFilter = $filters['tanggal_booking'] ?? [];

        return [
            'search' => trim((string) $request->input('tableSearch', '')),
            'sortColumn' => $this->normalizeSortColumn($request->input('tableSortColumn')),
            'sortDirection' => $this->normalizeSortDirection($request->input('tableSortDirection')),
            'tanggalDari' => filled($tanggalFilter['dari'] ?? null) ? (string) $tanggalFilter['dari'] : null,
            'tanggalSampai' => filled($tanggalFilter['sampai'] ?? null) ? (string) $tanggalFilter['sampai'] : null,
        ];
    }

    public function makeFilename(string $extension): string
    {
        return 'laporan-booking-' . now()->format('Ymd-His') . '.' . ltrim($extension, '.');
    }

    private function normalizeSortColumn(mixed $sortColumn): string
    {
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
            return 'created_at';
        }

        return (string) $sortColumn;
    }

    private function normalizeSortDirection(mixed $sortDirection): string
    {
        if (! in_array($sortDirection, ['asc', 'desc'], true)) {
            return 'desc';
        }

        return (string) $sortDirection;
    }
}
