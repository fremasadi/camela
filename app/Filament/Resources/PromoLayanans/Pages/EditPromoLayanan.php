<?php

namespace App\Filament\Resources\PromoLayanans\Pages;

use App\Filament\Resources\PromoLayanans\PromoLayananResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPromoLayanan extends EditRecord
{
    protected static string $resource = PromoLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {

        return static::$resource::getUrl('index');
    }
}
