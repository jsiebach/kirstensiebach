<?php

namespace App\Filament\Resources\Pages\OutreachPages\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OutreachPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ]),

                Section::make('SEO Settings')
                    ->schema([
                        TextInput::make('meta_title')
                            ->required()
                            ->maxLength(255)
                            ->label('Meta Title'),
                        Textarea::make('meta_description')
                            ->maxLength(500)
                            ->rows(3)
                            ->label('Meta Description'),
                    ])
                    ->collapsible(),
            ]);
    }
}
