<?php

namespace App\Filament\Resources\Pages\PhotographyPages\Pages;

use App\Filament\Resources\Pages\PhotographyPages\PhotographyPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPhotographyPage extends EditRecord
{
    protected static string $resource = PhotographyPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
