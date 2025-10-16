<?php

namespace App\Filament\Resources\ScienceAbstracts\Pages;

use App\Filament\Resources\ScienceAbstracts\ScienceAbstractResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScienceAbstracts extends ListRecords
{
    protected static string $resource = ScienceAbstractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
