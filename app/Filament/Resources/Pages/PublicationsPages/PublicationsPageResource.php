<?php

namespace App\Filament\Resources\Pages\PublicationsPages;

use App\Filament\Resources\Pages\PublicationsPages\Pages\CreatePublicationsPage;
use App\Filament\Resources\Pages\PublicationsPages\Pages\EditPublicationsPage;
use App\Filament\Resources\Pages\PublicationsPages\Pages\ListPublicationsPages;
use App\Filament\Resources\Pages\PublicationsPages\Schemas\PublicationsPageForm;
use App\Filament\Resources\Pages\PublicationsPages\Tables\PublicationsPagesTable;
use App\Models\Pages\PublicationsPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PublicationsPageResource extends Resource
{
    protected static ?string $model = PublicationsPage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Publications Page';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return PublicationsPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublicationsPagesTable::configure($table);
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
            'index' => ListPublicationsPages::route('/'),
            'create' => CreatePublicationsPage::route('/create'),
            'edit' => EditPublicationsPage::route('/{record}/edit'),
        ];
    }
}
