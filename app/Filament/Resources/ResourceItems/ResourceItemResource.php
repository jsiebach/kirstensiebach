<?php

namespace App\Filament\Resources\ResourceItems;

use App\Filament\Resources\ResourceItems\Pages\CreateResourceItem;
use App\Filament\Resources\ResourceItems\Pages\EditResourceItem;
use App\Filament\Resources\ResourceItems\Pages\ListResourceItems;
use App\Filament\Resources\ResourceItems\Schemas\ResourceItemForm;
use App\Filament\Resources\ResourceItems\Tables\ResourceItemsTable;
use App\Models\ResourceItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ResourceItemResource extends Resource
{
    protected static ?string $model = ResourceItem::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $navigationLabel = 'Resources';

    protected static ?string $modelLabel = 'resource';

    protected static UnitEnum|string|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 16;

    public static function form(Schema $schema): Schema
    {
        return ResourceItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResourceItemsTable::configure($table);
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
            'index' => ListResourceItems::route('/'),
            'create' => CreateResourceItem::route('/create'),
            'edit' => EditResourceItem::route('/{record}/edit'),
        ];
    }
}
