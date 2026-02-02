<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('title')
                    ->maxLength(255)
                    ->label('Job Title'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Toggle::make('alumni')
                    ->label('Alumni'),
                Textarea::make('bio')
                    ->required()
                    ->rows(5),
                FileUpload::make('profile_picture')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg', 'image/pjpeg'])
                    ->disk('public')
                    ->directory('team')
                    ->label('Profile Picture')
                    ->required(),
            ]);
    }
}
