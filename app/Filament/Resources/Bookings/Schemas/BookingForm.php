<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_id')
                    ->default(null),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                DatePicker::make('tanggal_booking')
                    ->required(),
                TimePicker::make('jam_booking')
                    ->required(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('total_harga')
                    ->required()
                    ->numeric(),
                Select::make('jenis_pembayaran')
                    ->options(['dp' => 'Dp', 'lunas' => 'Lunas'])
                    ->required(),
                TextInput::make('total_pembayaran')
                    ->required()
                    ->numeric(),
            ]);
    }
}
