<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Pastes\PasteResource;
use App\Models\Paste;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestPastes extends TableWidget
{
    public function getTableHeading(): string
    {
        return __('safe_paste.admin.latest_pastes');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Paste::query()->with('user')->latest())
            ->columns([
                TextColumn::make('title')
                    ->label(__('safe_paste.common.title'))
                    ->placeholder(__('safe_paste.common.untitled'))
                    ->limit(28)
                    ->searchable(),
                TextColumn::make('slug')
                    ->fontFamily('mono')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label(__('safe_paste.admin.owner'))
                    ->placeholder(__('safe_paste.admin.guest')),
                TextColumn::make('visibility')
                    ->label(__('safe_paste.common.visibility'))
                    ->badge(),
                TextColumn::make('views_count')
                    ->label(__('safe_paste.common.views'))
                    ->numeric(),
                TextColumn::make('created_at')
                    ->since(),
            ])
            ->paginated([5, 10])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (Paste $record): string => PasteResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
