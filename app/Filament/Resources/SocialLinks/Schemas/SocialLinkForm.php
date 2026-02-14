<?php

namespace App\Filament\Resources\SocialLinks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SocialLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->maxLength(255)
                    ->label('Title'),
                TextInput::make('link')
                    ->required()
                    ->url()
                    ->label('Link URL'),
                TextInput::make('icon')
                    ->required()
                    ->maxLength(255)
                    ->label('Icon Class')
                    ->helperText('Font Awesome class, e.g., "fa-twitter"'),
            ]);
    }
}
