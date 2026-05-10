<?php

namespace App\Filament\Resources\Pastes\Pages;

use App\Filament\Resources\Pastes\PasteResource;
use App\Models\Paste;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreatePaste extends CreateRecord
{
    protected static string $resource = PasteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : $this->generateUniqueSlug();
        $data['content'] = Crypt::encryptString($data['content']);
        $data['password_hash'] = filled($data['password'] ?? null) ? Hash::make($data['password']) : null;
        unset($data['password']);

        if (blank($data['user_id'] ?? null)) {
            $data['user_id'] = null;
            $data['delete_token'] = Str::random(64);
            $data['visibility'] = $data['visibility'] === 'private' ? 'unlisted' : $data['visibility'];
        }

        return $data;
    }

    private function generateUniqueSlug(): string
    {
        do {
            $slug = Str::random(12);
        } while (Paste::where('slug', $slug)->exists());

        return $slug;
    }
}
