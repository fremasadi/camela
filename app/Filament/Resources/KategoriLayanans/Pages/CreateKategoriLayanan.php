<?php

namespace App\Filament\Resources\KategoriLayanans\Pages;

use App\Filament\Resources\KategoriLayanans\KategoriLayananResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKategoriLayanan extends CreateRecord
{
    protected static string $resource = KategoriLayananResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {

        return static::$resource::getUrl('index');
    }
}
