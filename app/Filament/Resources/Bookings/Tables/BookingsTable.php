<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Filament\Exports\BookingExporter;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                TextColumn::make('pegawai.name')
                    ->label('Pegawai')
                    ->searchable()
                    ->default('-'),
                TextColumn::make('tanggal_booking')
                    ->date()
                    ->sortable(),
                TextColumn::make('jam_booking')
                    ->time()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('total_harga')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable()
                    ->summarize([
                        Sum::make()
                            ->label('Total Pendapatan')
                            ->query(fn (Builder $query): Builder => $query->where('status', 'confirmed'))
                            ->numeric(thousandsSeparator: '.')
                            ->prefix('Rp '),
                    ]),
                TextColumn::make('jenis_pembayaran')
                    ->badge(),
                TextColumn::make('total_pembayaran')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp ')
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
                Filter::make('tanggal_booking')
                    ->label('Filter Tanggal Booking')
                    ->form([
                        DatePicker::make('dari')
                            ->label('Dari tanggal'),
                        DatePicker::make('sampai')
                            ->label('Sampai tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_booking', '>=', $date),
                            )
                            ->when(
                                $data['sampai'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_booking', '<=', $date),
                            );
                    }),
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
                Action::make('assign_pegawai')
                    ->label('Assign Pegawai')
                    ->icon('heroicon-o-user')
                    ->color('warning')
                    ->modalHeading('Assign Pegawai ke Booking')
                    ->modalWidth('sm')
                    ->form([
                        Select::make('pegawai_id')
                            ->label('Pilih Pegawai')
                            ->options(
                                Pegawai::where('status', 'aktif')
                                    ->pluck('name', 'id')
                            )
                            ->required()
                            ->searchable(),
                    ])
                    ->fillForm(fn($record) => [
                        'pegawai_id' => $record->pegawai_id,
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(['pegawai_id' => $data['pegawai_id']]);
                    })
                    ->successNotificationTitle('Pegawai berhasil di-assign'),
            ])
            ->toolbarActions([
                ExportAction::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->exporter(BookingExporter::class)
                    ->columnMapping(false)
                    ->formats([ExportFormat::Xlsx]),
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('danger')
                    ->url(
                        fn(): string => URL::temporarySignedRoute(
                            'bookings.export.pdf',
                            now()->addMinutes(5),
                            request()->query(),
                        ),
                        shouldOpenInNewTab: true,
                    ),
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
