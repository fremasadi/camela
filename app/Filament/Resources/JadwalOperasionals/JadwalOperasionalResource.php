<?php

namespace App\Filament\Resources\JadwalOperasionals;

use App\Filament\Resources\JadwalOperasionals\Pages\CreateJadwalOperasional;
use App\Filament\Resources\JadwalOperasionals\Pages\EditJadwalOperasional;
use App\Filament\Resources\JadwalOperasionals\Pages\ListJadwalOperasionals;
use App\Filament\Resources\JadwalOperasionals\Schemas\JadwalOperasionalForm;
use App\Filament\Resources\JadwalOperasionals\Tables\JadwalOperasionalsTable;
use App\Models\JadwalOperasional;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

use Filament\Tables\Table;

class JadwalOperasionalResource extends Resource
{
    protected static ?string $model = JadwalOperasional::class;

 protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static UnitEnum|string|null $navigationGroup = 'Layanan & Jadwal';
    protected static ?string $navigationLabel = 'Jadwal Operasional';
    public static function form(Schema $schema): Schema
    {
        return JadwalOperasionalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JadwalOperasionalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJadwalOperasionals::route('/'),
            'create' => CreateJadwalOperasional::route('/create'),
            'edit' => EditJadwalOperasional::route('/{record}/edit'),
        ];
    }
}
