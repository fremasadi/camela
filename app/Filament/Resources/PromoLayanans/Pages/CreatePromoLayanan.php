<?php

namespace App\Filament\Resources\PromoLayanans\Pages;

use App\Filament\Resources\PromoLayanans\PromoLayananResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePromoLayanan extends CreateRecord
{
    protected static string $resource = PromoLayananResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {

        return static::$resource::getUrl('index');
    }
}
