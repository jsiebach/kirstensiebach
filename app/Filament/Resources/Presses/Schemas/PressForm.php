<?php

namespace App\Filament\Resources\Presses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Press Title'),
                TextInput::make('link')
                    ->required()
                    ->url()
                    ->label('Press Link'),
                DatePicker::make('date')
                    ->required()
                    ->label('Date'),
            ]);
    }
}
