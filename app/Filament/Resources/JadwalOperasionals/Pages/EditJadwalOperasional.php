<?php

namespace App\Filament\Resources\JadwalOperasionals\Pages;

use App\Filament\Resources\JadwalOperasionals\JadwalOperasionalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJadwalOperasional extends EditRecord
{
    protected static string $resource = JadwalOperasionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
