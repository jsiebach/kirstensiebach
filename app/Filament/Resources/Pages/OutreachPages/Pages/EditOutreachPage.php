<?php

namespace App\Filament\Resources\Pages\OutreachPages\Pages;

use App\Filament\Resources\Pages\OutreachPages\OutreachPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOutreachPage extends EditRecord
{
    protected static string $resource = OutreachPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
