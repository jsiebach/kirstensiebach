<?php

namespace App\Filament\Resources\Presses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('page_id')
                    ->relationship('page', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Outreach Page'),
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
