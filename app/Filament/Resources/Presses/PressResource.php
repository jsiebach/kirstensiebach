<?php

namespace App\Filament\Resources\Presses;

use App\Filament\Resources\Presses\Pages\CreatePress;
use App\Filament\Resources\Presses\Pages\EditPress;
use App\Filament\Resources\Presses\Pages\ListPresses;
use App\Filament\Resources\Presses\Schemas\PressForm;
use App\Filament\Resources\Presses\Tables\PressesTable;
use App\Models\Press;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PressResource extends Resource
{
    protected static ?string $model = Press::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Press';

    protected static ?int $navigationSort = 14;

    public static function form(Schema $schema): Schema
    {
        return PressForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PressesTable::configure($table);
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
            'index' => ListPresses::route('/'),
            'create' => CreatePress::route('/create'),
            'edit' => EditPress::route('/{record}/edit'),
        ];
    }
}
