<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalPendapatan = Booking::whereHas('pembayaran', fn ($q) =>
            $q->whereIn('transaction_status', ['settlement', 'capture', 'success'])
        )->sum('total_harga');

        $totalBooking   = Booking::count();
        $bookingPending = Booking::where('status', 'pending')->count();
        $bookingConfirmed = Booking::where('status', 'confirmed')->count();

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Dari booking yang lunas')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Total Booking', $totalBooking)
                ->description('Semua booking masuk')
                ->icon('heroicon-o-calendar')
                ->color('info'),

            Stat::make('Menunggu Konfirmasi', $bookingPending)
                ->description('Status pending')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Booking Dikonfirmasi', $bookingConfirmed)
                ->description('Status confirmed')
                ->icon('heroicon-o-check-circle')
                ->color('primary'),
        ];
    }
}
