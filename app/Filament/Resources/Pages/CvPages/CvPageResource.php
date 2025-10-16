<?php

namespace App\Filament\Resources\Pages\CvPages;

use App\Filament\Resources\Pages\CvPages\Pages\CreateCvPage;
use App\Filament\Resources\Pages\CvPages\Pages\EditCvPage;
use App\Filament\Resources\Pages\CvPages\Pages\ListCvPages;
use App\Filament\Resources\Pages\CvPages\Schemas\CvPageForm;
use App\Filament\Resources\Pages\CvPages\Tables\CvPagesTable;
use App\Models\Pages\CvPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CvPageResource extends Resource
{
    protected static ?string $model = CvPage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'CV Page';

    protected static UnitEnum|string|null $navigationGroup = 'Pages';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return CvPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CvPagesTable::configure($table);
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
            'index' => ListCvPages::route('/'),
            'create' => CreateCvPage::route('/create'),
            'edit' => EditCvPage::route('/{record}/edit'),
        ];
    }
}
