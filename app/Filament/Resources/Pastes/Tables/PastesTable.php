<?php

namespace App\Filament\Resources\Pastes\Tables;

use App\Models\Paste;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PastesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('safe_paste.common.title'))
                    ->placeholder('Untitled')
                    ->searchable()
                    ->sortable()
                    ->limit(32),
                TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label(__('safe_paste.admin.owner'))
                    ->placeholder(__('safe_paste.admin.guest'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('visibility')
                    ->label(__('safe_paste.common.visibility'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'private' => 'danger',
                        'public' => 'success',
                        default => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('language')
                    ->label(__('safe_paste.common.language'))
                    ->badge()
                    ->sortable(),
                IconColumn::make('password_hash')
                    ->label(__('safe_paste.common.password'))
                    ->boolean()
                    ->state(fn (Paste $record): bool => $record->isPasswordProtected()),
                TextColumn::make('views_count')
                    ->label(__('safe_paste.common.views'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('safe_paste.common.status'))
                    ->badge()
                    ->state(fn (Paste $record): string => $record->statusLabel())
                    ->color(fn (string $state): string => match ($state) {
                        __('safe_paste.status.active') => 'success',
                        __('safe_paste.status.expired'), __('safe_paste.status.burned'), __('safe_paste.status.view_limit') => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('expires_at')
                    ->label(__('safe_paste.common.expires'))
                    ->dateTime()
                    ->placeholder(__('safe_paste.common.never'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('safe_paste.common.created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('visibility')
                    ->options([
                        'private' => __('safe_paste.visibility.private'),
                        'unlisted' => __('safe_paste.visibility.unlisted'),
                        'public' => __('safe_paste.visibility.public'),
                    ]),
                SelectFilter::make('language')
                    ->options(array_combine(Paste::LANGUAGES, array_map('ucfirst', Paste::LANGUAGES))),
                SelectFilter::make('user_id')
                    ->label(__('safe_paste.admin.owner'))
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('guest')
                    ->label(__('safe_paste.admin.guest_paste'))
                    ->queries(
                        true: fn (Builder $query): Builder => $query->whereNull('user_id'),
                        false: fn (Builder $query): Builder => $query->whereNotNull('user_id'),
                    ),
                TernaryFilter::make('password_hash')
                    ->label(__('safe_paste.admin.password_protected'))
                    ->queries(
                        true: fn (Builder $query): Builder => $query->whereNotNull('password_hash'),
                        false: fn (Builder $query): Builder => $query->whereNull('password_hash'),
                    ),
                Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('expires_at')->where('expires_at', '<=', now())),
                Filter::make('burned')
                    ->query(fn (Builder $query): Builder => $query->where('burn_after_reading', true)->whereNotNull('read_at')),
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
