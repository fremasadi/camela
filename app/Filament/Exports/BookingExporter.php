<?php

namespace App\Filament\Exports;

use App\Models\Booking;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class BookingExporter extends Exporter
{
    protected static ?string $model = Booking::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('order_id')
                ->label('Order ID'),
            ExportColumn::make('user.name')
                ->label('Pelanggan'),
            ExportColumn::make('pegawai.name')
                ->label('Pegawai')
                ->formatStateUsing(fn ($state): string => $state ?: '-'),
            ExportColumn::make('tanggal_booking')
                ->label('Tanggal Booking')
                ->formatStateUsing(fn ($state): string => blank($state) ? '-' : \Carbon\Carbon::parse($state)->format('d/m/Y')),
            ExportColumn::make('jam_booking')
                ->label('Jam Booking')
                ->formatStateUsing(fn ($state): string => blank($state) ? '-' : \Carbon\Carbon::parse($state)->format('H:i')),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('jenis_pembayaran')
                ->label('Jenis Pembayaran'),
            ExportColumn::make('total_harga')
                ->label('Total Harga')
                ->formatStateUsing(fn ($state): string => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            ExportColumn::make('total_pembayaran')
                ->label('Total Pembayaran')
                ->formatStateUsing(fn ($state): string => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            ExportColumn::make('created_at')
                ->label('Dibuat Pada')
                ->formatStateUsing(fn ($state): string => blank($state) ? '-' : \Carbon\Carbon::parse($state)->format('d/m/Y H:i')),
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $successfulRows = number_format($export->successful_rows);

        return "Export booking selesai. {$successfulRows} baris berhasil diexport.";
    }
}
