<?php

namespace App\Filament\Resources\Pages\CvPages\Pages;

use App\Filament\Resources\Pages\CvPages\CvPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCvPages extends ListRecords
{
    protected static string $resource = CvPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
