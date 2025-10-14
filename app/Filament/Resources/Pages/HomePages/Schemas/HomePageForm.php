<?php

namespace App\Filament\Resources\Pages\HomePages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class HomePageForm
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

                Section::make('Content')
                    ->schema([
                        TextInput::make('content.tagline')
                            ->maxLength(255)
                            ->label('Tagline'),
                        FileUpload::make('content.banner')
                            ->image()
                            ->disk('public')
                            ->directory('pages')
                            ->label('Banner Image'),
                        FileUpload::make('content.profile_picture')
                            ->image()
                            ->disk('public')
                            ->directory('pages')
                            ->label('Profile Picture'),
                        Textarea::make('content.profile_summary')
                            ->rows(4)
                            ->label('Profile Summary'),
                        MarkdownEditor::make('content.bio')
                            ->label('Bio'),
                    ]),

                Section::make('Call to Action')
                    ->schema([
                        Toggle::make('content.add_call_to_action_banner')
                            ->label('Add CTA Banner')
                            ->reactive(),
                        Textarea::make('content.call_to_action')
                            ->rows(3)
                            ->label('Call to Action')
                            ->visible(fn (Get $get): bool => $get('content.add_call_to_action_banner') === true),
                        TextInput::make('content.action_link')
                            ->url()
                            ->label('Action Link')
                            ->visible(fn (Get $get): bool => $get('content.add_call_to_action_banner') === true),
                        TextInput::make('content.action_text')
                            ->maxLength(100)
                            ->label('Action Text')
                            ->visible(fn (Get $get): bool => $get('content.add_call_to_action_banner') === true),
                    ])
                    ->collapsible(),
            ]);
    }
}
