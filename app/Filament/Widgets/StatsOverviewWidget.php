<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Pegawai;
use App\Models\Pembayaran;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPendapatan = Pembayaran::paid()->sum('gross_amount');

        $pendapatanBulanIni = Pembayaran::paid()
            ->whereMonth('settlement_time', now()->month)
            ->whereYear('settlement_time', now()->year)
            ->sum('gross_amount');

        $bookingHariIni = Booking::whereDate('tanggal_booking', today())->count();
        $bookingBulanIni = Booking::whereMonth('tanggal_booking', now()->month)
            ->whereYear('tanggal_booking', now()->year)
            ->count();

        $totalCustomer = User::where('role', 'customer')->count();
        $pegawaiAktif  = Pegawai::where('status', 'aktif')->count();

        $bookingPending   = Booking::where('status', 'pending')->count();
        $bookingConfirmed = Booking::where('status', 'confirmed')->count();

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Bulan ini: Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'))
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Booking Hari Ini', $bookingHariIni)
                ->description('Bulan ini: ' . $bookingBulanIni . ' booking')
                ->icon('heroicon-o-calendar-days')
                ->color('info'),

            Stat::make('Booking Pending', $bookingPending)
                ->description($bookingConfirmed . ' sudah dikonfirmasi')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Total Customer', $totalCustomer)
                ->description($pegawaiAktif . ' pegawai aktif')
                ->icon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
