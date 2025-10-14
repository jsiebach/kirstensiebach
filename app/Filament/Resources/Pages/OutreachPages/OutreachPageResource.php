<?php

namespace App\Filament\Resources\Pages\OutreachPages;

use App\Filament\Resources\Pages\OutreachPages\Pages\CreateOutreachPage;
use App\Filament\Resources\Pages\OutreachPages\Pages\EditOutreachPage;
use App\Filament\Resources\Pages\OutreachPages\Pages\ListOutreachPages;
use App\Filament\Resources\Pages\OutreachPages\Schemas\OutreachPageForm;
use App\Filament\Resources\Pages\OutreachPages\Tables\OutreachPagesTable;
use App\Models\Pages\OutreachPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OutreachPageResource extends Resource
{
    protected static ?string $model = OutreachPage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Outreach Page';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return OutreachPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OutreachPagesTable::configure($table);
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
            'index' => ListOutreachPages::route('/'),
            'create' => CreateOutreachPage::route('/create'),
            'edit' => EditOutreachPage::route('/{record}/edit'),
        ];
    }
}
