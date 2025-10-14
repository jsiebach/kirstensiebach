<?php

namespace App\Filament\Resources\Pages\ResearchPages\Pages;

use App\Filament\Resources\Pages\ResearchPages\ResearchPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResearchPage extends EditRecord
{
    protected static string $resource = ResearchPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
