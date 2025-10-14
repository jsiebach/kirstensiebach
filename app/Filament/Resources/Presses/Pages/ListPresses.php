<?php

namespace App\Filament\Resources\Presses\Pages;

use App\Filament\Resources\Presses\PressResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPresses extends ListRecords
{
    protected static string $resource = PressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
