<?php

namespace App\Filament\Resources\PromoLayanans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Form;
class PromoLayananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('layanan_id')
                    ->label('Layanan')
                    ->relationship('layanan', 'name') // otomatis ambil dari relasi
                    ->required(),
                TextInput::make('diskon_persen')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal_mulai')
                    ->required(),
                DatePicker::make('tanggal_selesai')
                    ->required(),
            ]);
    }
}
