<?php

namespace App\Filament\Resources\Pages\ResearchPages;

use App\Filament\Resources\Pages\ResearchPages\Pages\CreateResearchPage;
use App\Filament\Resources\Pages\ResearchPages\Pages\EditResearchPage;
use App\Filament\Resources\Pages\ResearchPages\Pages\ListResearchPages;
use App\Filament\Resources\Pages\ResearchPages\Schemas\ResearchPageForm;
use App\Filament\Resources\Pages\ResearchPages\Tables\ResearchPagesTable;
use App\Models\Pages\ResearchPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ResearchPageResource extends Resource
{
    protected static ?string $model = ResearchPage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Research Page';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ResearchPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResearchPagesTable::configure($table);
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
            'index' => ListResearchPages::route('/'),
            'create' => CreateResearchPage::route('/create'),
            'edit' => EditResearchPage::route('/{record}/edit'),
        ];
    }
}
