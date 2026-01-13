<?php

namespace App\Filament\Resources\Layanans\Pages;

use App\Filament\Resources\Layanans\LayananResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLayanan extends CreateRecord
{
    protected static string $resource = LayananResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {

        return static::$resource::getUrl('index');
    }
}
