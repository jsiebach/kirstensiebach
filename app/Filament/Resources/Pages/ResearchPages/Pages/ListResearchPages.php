<?php

namespace App\Filament\Resources\Pages\ResearchPages\Pages;

use App\Filament\Resources\Pages\ResearchPages\ResearchPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResearchPages extends ListRecords
{
    protected static string $resource = ResearchPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
