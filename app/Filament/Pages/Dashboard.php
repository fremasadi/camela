<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BookingChartWidget;
use App\Filament\Widgets\LatestBookingsWidget;
use App\Filament\Widgets\LayananSeringDipesanWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            BookingChartWidget::class,
            LayananSeringDipesanWidget::class,
            LatestBookingsWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}
