<?php

namespace App\Filament\Resources\KategoriLayanans;

use App\Filament\Resources\KategoriLayanans\Pages\CreateKategoriLayanan;
use App\Filament\Resources\KategoriLayanans\Pages\EditKategoriLayanan;
use App\Filament\Resources\KategoriLayanans\Pages\ListKategoriLayanans;
use App\Filament\Resources\KategoriLayanans\Schemas\KategoriLayananForm;
use App\Filament\Resources\KategoriLayanans\Tables\KategoriLayanansTable;
use App\Models\KategoriLayanan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KategoriLayananResource extends Resource
{
    protected static ?string $model = KategoriLayanan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static UnitEnum|string|null $navigationGroup = 'Layanan & Jadwal';
    protected static ?string $navigationLabel = 'Kategori Layanan';
    public static function form(Schema $schema): Schema
    {
        return KategoriLayananForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategoriLayanansTable::configure($table);
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
            'index' => ListKategoriLayanans::route('/'),
            'create' => CreateKategoriLayanan::route('/create'),
            'edit' => EditKategoriLayanan::route('/{record}/edit'),
        ];
    }
}
