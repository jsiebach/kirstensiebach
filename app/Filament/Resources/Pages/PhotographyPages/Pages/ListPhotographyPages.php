<?php

namespace App\Filament\Resources\Pages\PhotographyPages\Pages;

use App\Filament\Resources\Pages\PhotographyPages\PhotographyPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPhotographyPages extends ListRecords
{
    protected static string $resource = PhotographyPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
