<?php

namespace App\Filament\Resources\Pastes;

use App\Filament\Resources\Pastes\Pages\CreatePaste;
use App\Filament\Resources\Pastes\Pages\EditPaste;
use App\Filament\Resources\Pastes\Pages\ListPastes;
use App\Filament\Resources\Pastes\Pages\ViewPaste;
use App\Filament\Resources\Pastes\Schemas\PasteForm;
use App\Filament\Resources\Pastes\Schemas\PasteInfolist;
use App\Filament\Resources\Pastes\Tables\PastesTable;
use App\Models\Paste;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PasteResource extends Resource
{
    protected static ?string $model = Paste::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationLabel(): string
    {
        return __('safe_paste.admin.pastes');
    }

    public static function getModelLabel(): string
    {
        return __('safe_paste.admin.paste');
    }

    public static function getPluralModelLabel(): string
    {
        return __('safe_paste.admin.pastes');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user');
    }

    public static function form(Schema $schema): Schema
    {
        return PasteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PasteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PastesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPastes::route('/'),
            'create' => CreatePaste::route('/create'),
            'view' => ViewPaste::route('/{record}'),
            'edit' => EditPaste::route('/{record}/edit'),
        ];
    }
}
