<?php

namespace App\Filament\Resources\Pastes\Pages;

use App\Filament\Resources\Pastes\PasteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class EditPaste extends EditRecord
{
    protected static string $resource = PasteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['content'] = $this->getRecord()->decryptContent();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['content'] = Crypt::encryptString($data['content']);

        if (filled($data['password'] ?? null)) {
            $data['password_hash'] = Hash::make($data['password']);
        }

        unset($data['password']);

        return $data;
    }
}
