<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->defaultSort('created_at', 'desc')

            ->columns([
                TextColumn::make('order_id')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('tanggal_booking')
                    ->date()
                    ->sortable(),
                TextColumn::make('jam_booking')
                    ->time()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('total_harga')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jenis_pembayaran')
                    ->badge(),
                TextColumn::make('total_pembayaran')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                ->label('Detail')
                ->modalHeading('Detail Booking')
                ->modalWidth('4xl')
                ->modalContent(function ($record) {
                    return view('filament.bookings.detail-modal', [
                        'booking' => $record,
                        'details' => $record->details()->with('layanan')->get(),
                    ]);
                }),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
