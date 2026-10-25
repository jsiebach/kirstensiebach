<?php

namespace App\Filament\Resources\Pages\ResourcesPages\Pages;

use App\Filament\Resources\Pages\ResourcesPages\ResourcesPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResourcesPages extends ListRecords
{
    protected static string $resource = ResourcesPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
