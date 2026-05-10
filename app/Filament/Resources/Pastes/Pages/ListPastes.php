<?php

namespace App\Filament\Resources\Pastes\Pages;

use App\Filament\Resources\Pastes\PasteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPastes extends ListRecords
{
    protected static string $resource = PasteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
