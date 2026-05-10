<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('safe_paste.admin.account'))
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->helperText('Leave empty on edit to keep the current password.'),
                        Toggle::make('is_admin')
                            ->label(__('safe_paste.admin.admin_access'))
                            ->helperText(__('safe_paste.admin.admin_access_help')),
                        DateTimePicker::make('email_verified_at')
                            ->label(__('safe_paste.admin.email_verified_at')),
                    ])
                    ->columns(2),
            ]);
    }
}
