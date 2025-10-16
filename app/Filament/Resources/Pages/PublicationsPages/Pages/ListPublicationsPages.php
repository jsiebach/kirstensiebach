<?php

namespace App\Filament\Resources\Pages\PublicationsPages\Pages;

use App\Filament\Resources\Pages\PublicationsPages\PublicationsPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPublicationsPages extends ListRecords
{
    protected static string $resource = PublicationsPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
