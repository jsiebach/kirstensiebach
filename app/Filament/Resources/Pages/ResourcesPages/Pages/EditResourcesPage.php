<?php

namespace App\Filament\Resources\Pages\ResourcesPages\Pages;

use App\Filament\Resources\Pages\ResourcesPages\ResourcesPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResourcesPage extends EditRecord
{
    protected static string $resource = ResourcesPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
