<?php

namespace App\Filament\Resources\KategoriLayanans\Pages;

use App\Filament\Resources\KategoriLayanans\KategoriLayananResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKategoriLayanan extends EditRecord
{
    protected static string $resource = KategoriLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
