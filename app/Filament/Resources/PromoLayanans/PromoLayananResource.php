<?php

namespace App\Filament\Resources\PromoLayanans;

use App\Filament\Resources\PromoLayanans\Pages\CreatePromoLayanan;
use App\Filament\Resources\PromoLayanans\Pages\EditPromoLayanan;
use App\Filament\Resources\PromoLayanans\Pages\ListPromoLayanans;
use App\Filament\Resources\PromoLayanans\Schemas\PromoLayananForm;
use App\Filament\Resources\PromoLayanans\Tables\PromoLayanansTable;
use App\Models\PromoLayanan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PromoLayananResource extends Resource
{
    protected static ?string $model = PromoLayanan::class;

protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';
    protected static UnitEnum|string|null $navigationGroup = 'Layanan & Jadwal';
    protected static ?string $navigationLabel = 'Promo Layanan';


    public static function form(Schema $schema): Schema
    {
        return PromoLayananForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromoLayanansTable::configure($table);
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
            'index' => ListPromoLayanans::route('/'),
            'create' => CreatePromoLayanan::route('/create'),
            'edit' => EditPromoLayanan::route('/{record}/edit'),
        ];
    }
}
