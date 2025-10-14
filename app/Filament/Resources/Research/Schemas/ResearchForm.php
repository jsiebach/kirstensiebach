<?php

namespace App\Filament\Resources\Research\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ResearchForm
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
                    ->label('Research Page'),
                TextInput::make('project_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Project Name'),
                Textarea::make('description')
                    ->required()
                    ->rows(5),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('research')
                    ->label('Project Image'),
            ]);
    }
}
