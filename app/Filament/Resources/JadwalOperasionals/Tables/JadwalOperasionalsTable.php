<?php

namespace App\Filament\Resources\JadwalOperasionals\Tables;

use App\Models\JadwalOperasional;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JadwalOperasionalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByRaw("
                CASE hari
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    WHEN 'sabtu' THEN 6
                    WHEN 'minggu' THEN 7
                    ELSE 8
                END
            "))
            ->columns([
                TextColumn::make('hari')
                    ->label('Hari')
                    ->formatStateUsing(fn (?string $state): string => $state ? (JadwalOperasional::hariOptions()[$state] ?? ucfirst($state)) : '-')
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-o-calendar-days')
                    ->searchable(),
                TextColumn::make('jam_operasional')
                    ->label('Jam Operasional')
                    ->state(function (JadwalOperasional $record): string {
                        if ($record->status === 'tutup') {
                            return 'Tutup';
                        }

                        return date('H:i', strtotime((string) $record->jam_buka)).' - '.date('H:i', strtotime((string) $record->jam_tutup));
                    })
                    ->badge()
                    ->icon('heroicon-o-clock')
                    ->color(fn (JadwalOperasional $record): string => $record->status === 'buka' ? 'success' : 'gray'),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->badge()
                    ->color(fn (string $state): string => $state === 'buka' ? 'success' : 'danger')
                    ->icon(fn (string $state): string => $state === 'buka' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),
                TextColumn::make('keterangan')
                    ->label('Catatan')
                    ->placeholder('-')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning'),
            ]);
    }
}
