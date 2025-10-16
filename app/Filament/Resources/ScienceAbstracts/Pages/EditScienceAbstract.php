<?php

namespace App\Filament\Resources\ScienceAbstracts\Pages;

use App\Filament\Resources\ScienceAbstracts\ScienceAbstractResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScienceAbstract extends EditRecord
{
    protected static string $resource = ScienceAbstractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
