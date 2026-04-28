<?php

namespace App\Filament\Resources\JadwalOperasionals\Schemas;

use App\Models\JadwalOperasional;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class JadwalOperasionalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hari')
                    ->options(JadwalOperasional::hariOptions())
                    ->required()
                    ->unique(ignoreRecord: true),
                TimePicker::make('jam_buka')
                    ->required(),
                TimePicker::make('jam_tutup')
                    ->required(),
                Select::make('status')
                    ->options(['buka' => 'Buka', 'tutup' => 'Tutup'])
                    ->default('buka')
                    ->required(),
                TextInput::make('keterangan')
                    ->default(null),
            ]);
    }
}
