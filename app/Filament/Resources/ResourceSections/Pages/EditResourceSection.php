<?php

namespace App\Filament\Resources\ResourceSections\Pages;

use App\Filament\Resources\ResourceSections\ResourceSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResourceSection extends EditRecord
{
    protected static string $resource = ResourceSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
