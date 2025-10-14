<?php

namespace App\Filament\Resources\ScienceAbstracts;

use App\Filament\Resources\ScienceAbstracts\Pages\CreateScienceAbstract;
use App\Filament\Resources\ScienceAbstracts\Pages\EditScienceAbstract;
use App\Filament\Resources\ScienceAbstracts\Pages\ListScienceAbstracts;
use App\Filament\Resources\ScienceAbstracts\Schemas\ScienceAbstractForm;
use App\Filament\Resources\ScienceAbstracts\Tables\ScienceAbstractsTable;
use App\Models\ScienceAbstract;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ScienceAbstractResource extends Resource
{
    protected static ?string $model = ScienceAbstract::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'Science Abstracts';

    protected static ?int $navigationSort = 13;

    public static function form(Schema $schema): Schema
    {
        return ScienceAbstractForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScienceAbstractsTable::configure($table);
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
            'index' => ListScienceAbstracts::route('/'),
            'create' => CreateScienceAbstract::route('/create'),
            'edit' => EditScienceAbstract::route('/{record}/edit'),
        ];
    }
}
