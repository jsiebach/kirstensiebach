<?php

namespace App\Filament\Resources\Pages\PhotographyPages;

use App\Filament\Resources\Pages\PhotographyPages\Pages\CreatePhotographyPage;
use App\Filament\Resources\Pages\PhotographyPages\Pages\EditPhotographyPage;
use App\Filament\Resources\Pages\PhotographyPages\Pages\ListPhotographyPages;
use App\Filament\Resources\Pages\PhotographyPages\Schemas\PhotographyPageForm;
use App\Filament\Resources\Pages\PhotographyPages\Tables\PhotographyPagesTable;
use App\Models\Pages\PhotographyPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PhotographyPageResource extends Resource
{
    protected static ?string $model = PhotographyPage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationLabel = 'Photography Page';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return PhotographyPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhotographyPagesTable::configure($table);
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
            'index' => ListPhotographyPages::route('/'),
            'create' => CreatePhotographyPage::route('/create'),
            'edit' => EditPhotographyPage::route('/{record}/edit'),
        ];
    }
}
