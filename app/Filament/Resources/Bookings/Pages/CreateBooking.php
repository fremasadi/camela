<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {

        return static::$resource::getUrl('index');
    }
}
