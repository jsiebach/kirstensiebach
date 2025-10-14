<?php

namespace App\Filament\Resources\Pages\OutreachPages\Pages;

use App\Filament\Resources\Pages\OutreachPages\OutreachPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOutreachPages extends ListRecords
{
    protected static string $resource = OutreachPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
