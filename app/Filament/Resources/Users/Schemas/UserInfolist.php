<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('safe_paste.admin.account'))
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('safe_paste.common.name')),
                        TextEntry::make('email')
                            ->label(__('safe_paste.common.email'))
                            ->copyable(),
                        IconEntry::make('is_admin')
                            ->label(__('safe_paste.admin.admin_access'))
                            ->boolean(),
                        TextEntry::make('pastes_count')
                            ->label(__('safe_paste.admin.pastes'))
                            ->state(fn ($record): int => $record->pastes()->count()),
                        TextEntry::make('email_verified_at')
                            ->label(__('safe_paste.admin.email_verified_at'))
                            ->dateTime()
                            ->placeholder(__('safe_paste.admin.not_verified')),
                        TextEntry::make('created_at')
                            ->label(__('safe_paste.common.created'))
                            ->dateTime(),
                    ])
                    ->columns(3),
            ]);
    }
}
