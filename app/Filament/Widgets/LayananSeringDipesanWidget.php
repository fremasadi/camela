<?php

namespace App\Filament\Widgets;

use App\Models\BookingDetail;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LayananSeringDipesanWidget extends BaseWidget
{
    protected static ?string $heading = 'Layanan Sering Dipesan';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BookingDetail::query()
                    ->select('booking_details.layanan_id')
                    ->selectRaw('MIN(booking_details.id) as id')
                    ->selectRaw('SUM(booking_details.qty) as total_dipesan')
                    ->selectRaw('COUNT(DISTINCT booking_details.booking_id) as total_booking')
                    ->with('layanan:id,name,harga')
                    ->groupBy('booking_details.layanan_id')
                    ->orderByDesc('total_dipesan')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('layanan.name')
                    ->label('Layanan')
                    ->searchable(),

                TextColumn::make('total_dipesan')
                    ->label('Jumlah Dipesan')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_booking')
                    ->label('Jumlah Booking')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('layanan.harga')
                    ->label('Harga')
                    ->prefix('Rp ')
                    ->numeric(thousandsSeparator: '.'),
            ]);
    }
}
