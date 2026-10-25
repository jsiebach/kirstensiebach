<?php

namespace App\Filament\Resources\ResourceSections\Pages;

use App\Filament\Resources\ResourceSections\ResourceSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResourceSections extends ListRecords
{
    protected static string $resource = ResourceSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
