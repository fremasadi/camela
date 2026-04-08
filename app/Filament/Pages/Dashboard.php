<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BookingChartWidget;
use App\Filament\Widgets\LatestBookingsWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
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
