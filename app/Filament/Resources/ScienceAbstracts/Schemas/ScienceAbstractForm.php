<?php

namespace App\Filament\Resources\ScienceAbstracts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ScienceAbstractForm
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
                    ->label('Publications Page'),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Abstract Title'),
                Textarea::make('authors')
                    ->required()
                    ->rows(2)
                    ->label('Authors'),
                TextInput::make('location')
                    ->required()
                    ->maxLength(255)
                    ->label('Conference/Event Location'),
                TextInput::make('city_state')
                    ->required()
                    ->maxLength(255)
                    ->label('City, State'),
                DatePicker::make('date')
                    ->required()
                    ->label('Presentation Date'),
                Textarea::make('details')
                    ->required()
                    ->rows(3)
                    ->label('Details'),
                TextInput::make('link')
                    ->url()
                    ->label('External Link'),
            ]);
    }
}
