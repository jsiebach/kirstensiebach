<?php

namespace App\Filament\Resources\ResourceItems\Pages;

use App\Filament\Resources\ResourceItems\ResourceItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResourceItem extends EditRecord
{
    protected static string $resource = ResourceItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
