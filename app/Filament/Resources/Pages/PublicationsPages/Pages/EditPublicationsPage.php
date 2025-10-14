<?php

namespace App\Filament\Resources\Pages\PublicationsPages\Pages;

use App\Filament\Resources\Pages\PublicationsPages\PublicationsPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPublicationsPage extends EditRecord
{
    protected static string $resource = PublicationsPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
