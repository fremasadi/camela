<?php

namespace App\Filament\Resources\Pembayarans\Pages;

use App\Filament\Resources\Pembayarans\PembayaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePembayaran extends CreateRecord
{
    protected static string $resource = PembayaranResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {

        return static::$resource::getUrl('index');
    }
}
