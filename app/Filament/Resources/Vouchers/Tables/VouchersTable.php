<?php

namespace App\Filament\Resources\Vouchers\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('required_points')
                    ->label('Point')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('discount_type')
                    ->label('Jenis')
                    ->badge(),

                TextColumn::make('discount_value')
                    ->label('Nilai Diskon')
                    ->numeric(thousandsSeparator: '.')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'gray'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
