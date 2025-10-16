<?php

namespace App\Filament\Resources\Pages\HomePages;

use App\Filament\Resources\Pages\HomePages\Pages\CreateHomePage;
use App\Filament\Resources\Pages\HomePages\Pages\EditHomePage;
use App\Filament\Resources\Pages\HomePages\Pages\ListHomePages;
use App\Filament\Resources\Pages\HomePages\Schemas\HomePageForm;
use App\Filament\Resources\Pages\HomePages\Tables\HomePagesTable;
use App\Models\Pages\HomePage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class HomePageResource extends Resource
{
    protected static ?string $model = HomePage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Home Page';

    protected static UnitEnum|string|null $navigationGroup = 'Pages';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return HomePageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomePagesTable::configure($table);
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
            'index' => ListHomePages::route('/'),
            'create' => CreateHomePage::route('/create'),
            'edit' => EditHomePage::route('/{record}/edit'),
        ];
    }
}
