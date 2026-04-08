<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BookingChartWidget;
use App\Filament\Widgets\LatestBookingsWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static Heroicon|string|null $navigationIcon = Heroicon::OutlinedHome;
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            BookingChartWidget::class,
            LatestBookingsWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}
