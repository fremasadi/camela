<?php

namespace App\Filament\Resources\JadwalOperasionals\Pages;

use App\Filament\Resources\JadwalOperasionals\JadwalOperasionalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalOperasional extends CreateRecord
{
    protected static string $resource = JadwalOperasionalResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
