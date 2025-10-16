<?php

namespace App\Filament\Resources\Pages\LabPages\Pages;

use App\Filament\Resources\Pages\LabPages\LabPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLabPages extends ListRecords
{
    protected static string $resource = LabPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
