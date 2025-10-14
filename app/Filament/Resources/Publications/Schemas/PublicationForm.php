<?php

namespace App\Filament\Resources\Publications\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PublicationForm
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
                    ->label('Publication Title'),
                TextInput::make('publication_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Journal/Conference Name'),
                Textarea::make('authors')
                    ->required()
                    ->rows(3)
                    ->label('Authors'),
                DatePicker::make('date_published')
                    ->required()
                    ->label('Date Published'),
                Toggle::make('published')
                    ->label('Published')
                    ->default(true),
                Textarea::make('abstract')
                    ->rows(5)
                    ->label('Abstract'),
                TextInput::make('link')
                    ->url()
                    ->label('External Link'),
                TextInput::make('doi')
                    ->label('DOI')
                    ->placeholder('10.1000/example'),
            ]);
    }
}
