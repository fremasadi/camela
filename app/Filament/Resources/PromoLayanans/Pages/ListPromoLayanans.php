<?php

namespace App\Filament\Resources\PromoLayanans\Pages;

use App\Filament\Resources\PromoLayanans\PromoLayananResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPromoLayanans extends ListRecords
{
    protected static string $resource = PromoLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    
}
