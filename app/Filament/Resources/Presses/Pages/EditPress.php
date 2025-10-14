<?php

namespace App\Filament\Resources\Presses\Pages;

use App\Filament\Resources\Presses\PressResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPress extends EditRecord
{
    protected static string $resource = PressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
