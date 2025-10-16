<?php

namespace App\Filament\Resources\Pages\CvPages\Pages;

use App\Filament\Resources\Pages\CvPages\CvPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCvPage extends EditRecord
{
    protected static string $resource = CvPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
