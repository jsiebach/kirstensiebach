<?php

namespace App\Filament\Resources\Research\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ResearchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('project_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Project Name'),
                Textarea::make('description')
                    ->required()
                    ->rows(5),
                FileUpload::make('image')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->disk('public')
                    ->directory('research')
                    ->label('Project Image'),
            ]);
    }
}
