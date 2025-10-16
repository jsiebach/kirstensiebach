<?php

namespace App\Filament\Resources\Pages\LabPages\Pages;

use App\Filament\Resources\Pages\LabPages\LabPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLabPage extends EditRecord
{
    protected static string $resource = LabPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
