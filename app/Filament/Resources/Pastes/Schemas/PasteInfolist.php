<?php

namespace App\Filament\Resources\Pastes\Schemas;

use App\Models\Paste;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PasteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('safe_paste.admin.details'))
                    ->schema([
                        TextEntry::make('title')
                            ->label(__('safe_paste.common.title'))
                            ->placeholder(__('safe_paste.common.untitled')),
                        TextEntry::make('slug')
                            ->copyable()
                            ->fontFamily('mono'),
                        TextEntry::make('user.email')
                            ->label(__('safe_paste.admin.owner'))
                            ->placeholder(__('safe_paste.admin.guest')),
                        TextEntry::make('visibility')
                            ->label(__('safe_paste.common.visibility'))
                            ->badge(),
                        TextEntry::make('language')
                            ->label(__('safe_paste.common.language'))
                            ->badge(),
                        TextEntry::make('status')
                            ->label(__('safe_paste.common.status'))
                            ->state(fn (Paste $record): string => $record->statusLabel())
                            ->badge(),
                        IconEntry::make('password_hash')
                            ->label(__('safe_paste.admin.password_protected'))
                            ->boolean()
                            ->state(fn (Paste $record): bool => $record->isPasswordProtected()),
                        IconEntry::make('burn_after_reading')
                            ->boolean(),
                    ])
                    ->columns(4),

                Section::make(__('safe_paste.admin.usage'))
                    ->schema([
                        TextEntry::make('views_count')
                            ->numeric(),
                        TextEntry::make('max_views')
                            ->placeholder(__('safe_paste.common.unlimited')),
                        TextEntry::make('expires_at')
                            ->dateTime()
                            ->placeholder(__('safe_paste.common.never')),
                        TextEntry::make('last_viewed_at')
                            ->label(__('safe_paste.admin.last_viewed_at'))
                            ->dateTime()
                            ->placeholder(__('safe_paste.common.never')),
                        TextEntry::make('read_at')
                            ->label(__('safe_paste.admin.read_at'))
                            ->dateTime()
                            ->placeholder(__('safe_paste.admin.not_burned')),
                        TextEntry::make('created_at')
                            ->dateTime(),
                    ])
                    ->columns(3),

                Section::make(__('safe_paste.common.content'))
                    ->schema([
                        TextEntry::make('content')
                            ->label(__('safe_paste.admin.decrypted_content'))
                            ->state(fn (Paste $record): string => $record->decryptContent())
                            ->copyable()
                            ->fontFamily('mono')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
