<?php

namespace App\Filament\Resources\KategoriLayanans\Pages;

use App\Filament\Resources\KategoriLayanans\KategoriLayananResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKategoriLayanans extends ListRecords
{
    protected static string $resource = KategoriLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
