<?php

namespace App\Filament\Resources\JadwalOperasionals\Pages;

use App\Filament\Resources\JadwalOperasionals\JadwalOperasionalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJadwalOperasionals extends ListRecords
{
    protected static string $resource = JadwalOperasionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
