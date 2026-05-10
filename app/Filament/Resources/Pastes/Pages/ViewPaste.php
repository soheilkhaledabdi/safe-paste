<?php

namespace App\Filament\Resources\Pastes\Pages;

use App\Filament\Resources\Pastes\PasteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPaste extends ViewRecord
{
    protected static string $resource = PasteResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['content'] = $this->getRecord()->decryptContent();

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
