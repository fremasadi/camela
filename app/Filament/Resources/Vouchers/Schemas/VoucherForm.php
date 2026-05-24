<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Voucher')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                TextInput::make('name')
                    ->label('Nama Voucher')
                    ->required()
                    ->maxLength(255),

                TextInput::make('required_points')
                    ->label('Point Dibutuhkan')
                    ->required()
                    ->integer()
                    ->minValue(1),

                Select::make('discount_type')
                    ->label('Jenis Diskon')
                    ->options([
                        'fixed' => 'Nominal',
                        'percent' => 'Persen',
                    ])
                    ->required()
                    ->default('fixed'),

                TextInput::make('discount_value')
                    ->label('Nilai Diskon')
                    ->required()
                    ->numeric()
                    ->minValue(0),

                TextInput::make('max_discount')
                    ->label('Maksimal Diskon')
                    ->numeric()
                    ->minValue(0),

                TextInput::make('min_transaction')
                    ->label('Minimal Transaksi')
                    ->numeric()
                    ->minValue(0),

                TextInput::make('expired_days')
                    ->label('Berlaku Berapa Hari')
                    ->required()
                    ->integer()
                    ->minValue(1)
                    ->default(30),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif',
                    ])
                    ->required()
                    ->default('active'),
            ]);
    }
}
