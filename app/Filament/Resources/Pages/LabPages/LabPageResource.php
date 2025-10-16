<?php

namespace App\Filament\Resources\Pages\LabPages;

use App\Filament\Resources\Pages\LabPages\Pages\CreateLabPage;
use App\Filament\Resources\Pages\LabPages\Pages\EditLabPage;
use App\Filament\Resources\Pages\LabPages\Pages\ListLabPages;
use App\Filament\Resources\Pages\LabPages\Schemas\LabPageForm;
use App\Filament\Resources\Pages\LabPages\Tables\LabPagesTable;
use App\Models\Pages\LabPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class LabPageResource extends Resource
{
    protected static ?string $model = LabPage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationLabel = 'Lab Page';

    protected static UnitEnum|string|null $navigationGroup = 'Pages';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return LabPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LabPagesTable::configure($table);
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
            'index' => ListLabPages::route('/'),
            'create' => CreateLabPage::route('/create'),
            'edit' => EditLabPage::route('/{record}/edit'),
        ];
    }
}
