<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBookingsWidget extends BaseWidget
{
    protected static ?string $heading = 'Booking Terbaru';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::with(['user', 'pegawai', 'pembayaran'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('order_id')
                    ->label('Order ID')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('pegawai.name')
                    ->label('Pegawai')
                    ->default('-'),

                TextColumn::make('tanggal_booking')
                    ->label('Tanggal')
                    ->date('d M Y'),

                TextColumn::make('jam_booking')
                    ->label('Jam')
                    ->time('H:i'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed'  => 'success',
                        'pending'    => 'warning',
                        'cancelled'  => 'danger',
                        'rejected'   => 'danger',
                        default      => 'gray',
                    }),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->prefix('Rp ')
                    ->numeric(thousandsSeparator: '.'),

                TextColumn::make('jenis_pembayaran')
                    ->label('Jenis')
                    ->badge(),
            ]);
    }
}
