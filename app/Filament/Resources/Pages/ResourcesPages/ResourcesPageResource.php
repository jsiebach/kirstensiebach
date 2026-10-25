<?php

namespace App\Filament\Resources\Pages\ResourcesPages;

use App\Filament\Resources\Pages\ResourcesPages\Pages\CreateResourcesPage;
use App\Filament\Resources\Pages\ResourcesPages\Pages\EditResourcesPage;
use App\Filament\Resources\Pages\ResourcesPages\Pages\ListResourcesPages;
use App\Filament\Resources\Pages\ResourcesPages\Schemas\ResourcesPageForm;
use App\Filament\Resources\Pages\ResourcesPages\Tables\ResourcesPagesTable;
use App\Models\Pages\ResourcesPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ResourcesPageResource extends Resource
{
    protected static ?string $model = ResourcesPage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $navigationLabel = 'Resources Page';

    protected static UnitEnum|string|null $navigationGroup = 'Pages';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return ResourcesPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResourcesPagesTable::configure($table);
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
            'index' => ListResourcesPages::route('/'),
            'create' => CreateResourcesPage::route('/create'),
            'edit' => EditResourcesPage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationUrl(): string
    {
        $record = ResourcesPage::where('slug', ResourcesPage::$slug)->first();

        return $record ? static::getUrl('edit', ['record' => $record]) : static::getUrl('index');
    }
}
