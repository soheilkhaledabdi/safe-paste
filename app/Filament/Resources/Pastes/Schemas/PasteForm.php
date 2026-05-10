<?php

namespace App\Filament\Resources\Pastes\Schemas;

use App\Models\Paste;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PasteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('safe_paste.admin.owner_access'))
                    ->schema([
                        Select::make('user_id')
                            ->label(__('safe_paste.admin.owner'))
                            ->relationship('user', 'email')
                            ->searchable()
                            ->preload()
                            ->placeholder(__('safe_paste.admin.guest_paste')),
                        TextInput::make('slug')
                            ->maxLength(32)
                            ->unique(ignoreRecord: true)
                            ->helperText(__('safe_paste.admin.leave_slug_empty')),
                        Select::make('visibility')
                            ->options([
                                'private' => __('safe_paste.visibility.private'),
                                'unlisted' => __('safe_paste.visibility.unlisted'),
                                'public' => __('safe_paste.visibility.public'),
                            ])
                            ->default('unlisted')
                            ->required(),
                        TextInput::make('password')
                            ->label(__('safe_paste.common.password'))
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->helperText(__('safe_paste.admin.leave_password_empty')),
                    ])
                    ->columns(2),

                Section::make(__('safe_paste.admin.paste'))
                    ->schema([
                        TextInput::make('title')
                            ->maxLength(255),
                        Select::make('language')
                            ->options(array_combine(Paste::LANGUAGES, array_map('ucfirst', Paste::LANGUAGES)))
                            ->default('text')
                            ->required(),
                        Textarea::make('content')
                            ->required()
                            ->rows(16)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('safe_paste.admin.limits'))
                    ->schema([
                        DateTimePicker::make('expires_at'),
                        TextInput::make('max_views')
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->maxValue(10000),
                        Toggle::make('burn_after_reading'),
                        DateTimePicker::make('read_at'),
                    ])
                    ->columns(2),
            ]);
    }
}
