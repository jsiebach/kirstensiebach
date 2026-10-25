<?php

namespace App\Filament\Resources\ResourceItems\Pages;

use App\Filament\Resources\ResourceItems\ResourceItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResourceItems extends ListRecords
{
    protected static string $resource = ResourceItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
