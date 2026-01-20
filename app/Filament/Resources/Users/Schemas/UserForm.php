<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                    TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(
                        table: 'users',
                        column: 'email',
                        ignoreRecord: true
                    ),

                TextInput::make('password')
                    ->password()
                    ->label('Password')

                    // Wajib hanya saat create
                    ->required(fn ($livewire) =>
                        $livewire instanceof \Filament\Resources\Pages\CreateRecord
                    )

                    // Hash otomatis kalau diisi
                    ->dehydrateStateUsing(fn ($state) =>
                        filled($state) ? bcrypt($state) : null
                    )

                    // Jangan update kalau kosong
                    ->dehydrated(fn ($state) => filled($state)),
                TextInput::make('no_telp')
                    ->label('Nomer Telefon')
                    ->tel()
                    ->default(null),
                Select::make('role')
                    ->label('Peran')
                    ->options(['admin' => 'Admin', 'customer' => 'Customer'])
                    ->default('customer')
                    ->required(),
            ]);
    }
}
