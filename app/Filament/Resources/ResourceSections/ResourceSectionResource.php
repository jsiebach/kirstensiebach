<?php

namespace App\Filament\Resources\ResourceSections;

use App\Filament\Resources\ResourceSections\Pages\CreateResourceSection;
use App\Filament\Resources\ResourceSections\Pages\EditResourceSection;
use App\Filament\Resources\ResourceSections\Pages\ListResourceSections;
use App\Filament\Resources\ResourceSections\Schemas\ResourceSectionForm;
use App\Filament\Resources\ResourceSections\Tables\ResourceSectionsTable;
use App\Models\ResourceSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ResourceSectionResource extends Resource
{
    protected static ?string $model = ResourceSection::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Resource Sections';

    protected static ?string $modelLabel = 'resource section';

    protected static UnitEnum|string|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 17;

    public static function form(Schema $schema): Schema
    {
        return ResourceSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResourceSectionsTable::configure($table);
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
            'index' => ListResourceSections::route('/'),
            'create' => CreateResourceSection::route('/create'),
            'edit' => EditResourceSection::route('/{record}/edit'),
        ];
    }
}
