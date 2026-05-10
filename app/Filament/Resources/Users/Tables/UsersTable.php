<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('safe_paste.common.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('safe_paste.common.email'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                IconColumn::make('is_admin')
                    ->label(__('safe_paste.admin.admin_access'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('pastes_count')
                    ->label(__('safe_paste.admin.pastes'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->label(__('safe_paste.admin.verified'))
                    ->dateTime()
                    ->placeholder(__('safe_paste.admin.not_verified'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('safe_paste.common.created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_admin')
                    ->label(__('safe_paste.admin.admin_access')),
                TernaryFilter::make('email_verified_at')
                    ->label(__('safe_paste.admin.verified'))
                    ->nullable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
