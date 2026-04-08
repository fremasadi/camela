<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BookingChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Booking 7 Hari Terakhir';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $days   = collect(range(6, 0))->map(fn ($i) => now()->subDays($i));
        $labels = $days->map(fn ($d) => $d->translatedFormat('D, d M'))->toArray();

        $bookings = $days->map(fn ($d) =>
            Booking::whereDate('tanggal_booking', $d->toDateString())->count()
        )->toArray();

        $pendapatan = $days->map(fn ($d) =>
            (float) Booking::whereDate('tanggal_booking', $d->toDateString())
                ->whereHas('pembayaran', fn ($q) => $q->paid())
                ->sum('total_harga')
        )->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Jumlah Booking',
                    'data'            => $bookings,
                    'borderColor'     => '#5c2707',
                    'backgroundColor' => 'rgba(92,39,7,0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                    'yAxisID'         => 'y',
                ],
                [
                    'label'           => 'Pendapatan (Rp)',
                    'data'            => $pendapatan,
                    'borderColor'     => '#f59e0b',
                    'backgroundColor' => 'rgba(245,158,11,0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                    'yAxisID'         => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'type'     => 'linear',
                    'display'  => true,
                    'position' => 'left',
                    'title'    => ['display' => true, 'text' => 'Booking'],
                ],
                'y1' => [
                    'type'     => 'linear',
                    'display'  => true,
                    'position' => 'right',
                    'title'    => ['display' => true, 'text' => 'Pendapatan (Rp)'],
                    'grid'     => ['drawOnChartArea' => false],
                ],
            ],
        ];
    }
}
